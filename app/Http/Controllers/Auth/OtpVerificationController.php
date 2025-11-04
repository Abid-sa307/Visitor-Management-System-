<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OtpVerificationController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show the OTP verification form.
     */
    public function show()
    {
        if (!Session::has('otp_required') || !Session::has('otp_user_id')) {
            return redirect()->route('login')->with('error', 'Invalid OTP verification request.');
        }
        
        $user = User::find(Session::get('otp_user_id'));
        
        if (!$user) {
            Session::forget(['otp_user_id', 'otp_required']);
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        // Store the email in the session for the view
        Session::put('otp_email', $user->email);
        
        // If OTP is already verified, redirect to dashboard
        if (Session::get('otp_verified', false)) {
            return $this->loginUser($user);
        }
        
        return view('auth.verify-otp', [
            'resendUrl' => route('otp.resend')
        ]);
    }

    /**
     * Verify the OTP code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        // Get the OTP from the request and ensure it's a string
        $otp = (string) $request->input('otp');
        
        // Log the received OTP for debugging
        \Log::info('Verifying OTP', [
            'otp_received' => $otp,
            'otp_length' => strlen($otp),
            'session_id' => $request->session()->getId()
        ]);

        $userId = $request->session()->get('otp_user_id');
        if (!$userId) {
            \Log::warning('OTP verification failed: No user ID in session');
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $user = User::find($userId);
        if (!$user) {
            $request->session()->forget(['otp_user_id', 'otp_required']);
            \Log::warning('OTP verification failed: User not found', ['user_id' => $userId]);
            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Log the OTP verification attempt
        \Log::info('Attempting OTP verification', [
            'user_id' => $user->id,
            'stored_otp' => $user->otp,
            'otp_expires_at' => $user->otp_expires_at,
            'current_time' => now()
        ]);

        if ($this->otpService->verifyOtp($user, $otp)) {
            // Mark OTP as verified in the session
            $request->session()->put('otp_verified', true);
            
            // Log the user in
            Auth::login($user);
            
            // Clear OTP session data
            $request->session()->forget(['otp_user_id', 'otp_required']);
            
            // Regenerate session ID for security
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'))
                ->with('status', 'Successfully logged in!');
        }

        return back()->withErrors([
            'otp' => 'The provided OTP is invalid or has expired.',
        ]);
    }

    /**
     * Resend the OTP code.
     */
    public function resend(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        if (!$userId) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Session expired. Please log in again.'], 401);
            }
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            $request->session()->forget(['otp_user_id', 'otp_required']);
            if ($request->expectsJson()) {
                return response()->json(['error' => 'User not found.'], 404);
            }
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        try {
            // Generate and send new OTP
            $this->otpService->generateAndSendOtp($user);
            
            $message = 'A new OTP has been sent to your email.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'email' => $user->email
                ]);
            }
            
            return back()->with('status', $message);
            
        } catch (\Exception $e) {
            \Log::error('Failed to resend OTP', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            $error = 'Failed to send OTP. Please try again.';
            
            if ($request->expectsJson()) {
                return response()->json(['error' => $error], 500);
            }
            
            return back()->with('error', $error);
        }
    }
    
    /**
     * Log in the user and redirect to dashboard
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loginUser($user)
    {
        // Log in the user
        Auth::login($user);
        
        // Clear OTP session data
        Session::forget(['otp_user_id', 'otp_required', 'otp_verified']);
        
        // Regenerate the session
        request()->session()->regenerate();
        
        return redirect()->intended(route('dashboard'))
            ->with('status', 'Successfully logged in!');
    }
}
