<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
        $this->middleware('guest')->except('logout');
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function showGuardLogin()
    {
        return view('auth.guard-login');
    }

    public function showOtpVerification()
    {
        if (!session('otp_required')) {
            return redirect()->route('admin.login');
        }
        return view('auth.verify-otp');
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Log login attempt
        Log::info('Login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'time' => now()
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Log user details
            Log::info('User authenticated', [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'is_super_admin' => $user->is_super_admin
            ]);

            // Check if user is admin or super admin and requires OTP
            if (($user->role === 'admin' || $user->is_super_admin) && !Session::get('otp_verified', false)) {
                // Generate and send OTP
                $otp = $this->otpService->generateAndSendOtp($user);
                
                if ($otp) {
                    // Store user ID in session for OTP verification
                    Session::put('otp_user_id', $user->id);
                    Session::put('otp_required', true);
                    Session::put('otp_email', $user->email);
                    
                    // Log the user out until OTP is verified
                    Auth::logout();
                    
                    // Log OTP generation
                    Log::info('OTP generated and sent', [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);
                    
                    // Redirect to OTP verification page
                    return redirect()->route('otp.verify')
                        ->with('status', 'Please enter the OTP sent to your email.');
                }
                
                // If OTP sending failed, log the error and continue with login
                Log::error('Failed to send OTP', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }
            
            // If no OTP is required or OTP is already verified, proceed with login
            return redirect()->intended(route('dashboard'))
                ->with('success', 'You have successfully logged in!');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = Session::get('otp_user_id');
        
        if (!$userId) {
            \Log::warning('OTP verification failed: No user ID in session');
            return redirect()->route('admin.login')->withErrors([
                'otp' => 'Session expired. Please log in again.'
            ]);
        }

        $user = User::find($userId);
        
        if (!$user) {
            \Log::warning('OTP verification failed: User not found', ['user_id' => $userId]);
            return redirect()->route('admin.login')->withErrors([
                'otp' => 'User not found. Please log in again.'
            ]);
        }

        $otp = $request->input('otp');
        \Log::info('Verifying OTP', [
            'user_id' => $user->id,
            'email' => $user->email,
            'otp_length' => strlen($otp)
        ]);

        if ($this->otpService->verifyOtp($user, $otp)) {
            // Clear OTP session data
            Session::forget(['otp_user_id', 'otp_required', 'otp_email']);
            
            // Log in the user
            Auth::login($user);
            
            // Log successful login
            \Log::info('OTP verification successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            // Clear any previous intended URL to prevent redirect loops
            Session::forget('url.intended');
            
            return redirect()->intended(route('dashboard'));
        }

        \Log::warning('OTP verification failed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => $otp
        ]);

        return back()->withErrors([
            'otp' => 'Invalid or expired OTP. Please try again.',
        ]);
    }

    /**
     * Resend OTP to the user
     */
    public function resendOtp(Request $request)
    {
        $userId = Session::get('otp_user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please log in again.'
            ], 401);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found. Please log in again.'
            ], 404);
        }

        try {
            // Generate and send new OTP
            $otp = $this->otpService->generateAndSendOtp($user);
            
            if ($otp) {
                return response()->json([
                    'success' => true,
                    'message' => 'New OTP has been sent to your email.'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
            
        } catch (\Exception $e) {
            \Log::error('Failed to resend OTP', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }
}
