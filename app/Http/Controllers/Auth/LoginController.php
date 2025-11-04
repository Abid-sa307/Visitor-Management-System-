<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
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
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // If user is a super admin, require OTP
            if ($user->role === 'admin' && $user->is_super_admin) {
                // Generate and send OTP
                $this->otpService->generateAndSendOtp($user);
                
                // Store user ID in session for OTP verification
                Session::put('otp_user_id', $user->id);
                Session::put('otp_required', true);
                
                // Logout the user until OTP is verified
                Auth::logout();
                
                return redirect()->route('otp.verify');
            }
            
            // For non-super admin or already verified users
            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            } elseif ($user->role === 'guard') {
                return redirect()->route('guard.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or role mismatch.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = Session::get('otp_user_id');
        $user = User::findOrFail($userId);
        $otp = $request->input('otp');

        if ($this->otpService->verifyOtp($user, $otp)) {
            // Clear OTP session data
            Session::forget(['otp_user_id', 'otp_required']);
            
            // Log in the user
            Auth::login($user);
            
            return redirect()->intended(route('dashboard'));
        }

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
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $user = User::findOrFail($userId);
        
        // Generate and send new OTP
        $this->otpService->generateAndSendOtp($user);
        
        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}
