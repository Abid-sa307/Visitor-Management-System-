<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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
    public function showOtpForm(Request $request)
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
        
        return view('auth.verify-otp');
    }

    /**
     * Verify the OTP code.
     */
    public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|string|size:6',
    ]);

    $otp = (string) $request->input('otp');
    $userId = $request->session()->get('otp_user_id');
    
    if (!$userId) {
        return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
    }

    $user = User::find($userId);
    if (!$user) {
        $request->session()->forget(['otp_user_id', 'otp_required']);
        return redirect()->route('login')->with('error', 'User not found.');
    }

    if ($this->otpService->verifyOtp($user, $otp)) {
        // Mark OTP as verified in the session
        $request->session()->put('otp_verified', true);
        $request->session()->forget(['otp_required', 'otp_user_id']);
        
        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();
        
        // Redirect to intended URL or dashboard
        return redirect()->intended(route('dashboard'))
            ->with('status', 'You have been successfully logged in!');
    }

    return back()->withErrors([
        'otp' => 'The provided OTP is invalid or has expired. Please try again.',
    ]);
}

    /**
     * Resend the OTP to the user.
     */
    public function resendOtp(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please log in again.'
            ], 401);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            $request->session()->forget(['otp_user_id', 'otp_required']);
            return response()->json([
                'success' => false,
                'message' => 'User not found. Please log in again.'
            ], 404);
        }
        
        try {
            // Generate and send new OTP
            $otp = $this->otpService->generateAndSendOtp($user);
            
            if ($otp) {
                Log::info('OTP resent successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'A new OTP has been sent to your email.'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending OTP. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Log the user in and redirect to the intended page.
     */
    protected function loginUser($user)
    {
        Auth::login($user);
        
        // Clear OTP session data
        Session::forget(['otp_user_id', 'otp_required', 'otp_verified', 'otp_email']);
        
        // Regenerate session ID for security
        request()->session()->regenerate();
        
        // Redirect to intended URL or dashboard
        return redirect()->intended(route('dashboard'))
            ->with('status', 'You have been successfully logged in!');
    }
}
