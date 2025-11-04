<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // If the user is already logged in, redirect them to their appropriate dashboard.
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }

        // Return the login view if the user is not logged in
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Get validated input data from the request
        $validated = $request->validated();
        $email = $validated['email'];
        
        \Log::info('Login attempt', ['email' => $email]);
        
        // First, validate the credentials
        $credentials = [
            'email' => $email,
            'password' => $validated['password']
        ];
        
        $remember = isset($validated['remember']) && $validated['remember'] === 'on';
        
        if (!Auth::attempt($credentials, $remember)) {
            \Log::warning('Login failed: Invalid credentials', ['email' => $email]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            \Log::error('User not found after successful authentication');
            Auth::logout();
            return back()->withErrors([
                'email' => 'An error occurred. Please try again.',
            ]);
        }
        
        \Log::info('User authenticated', [
            'user_id' => $user->id, 
            'email' => $user->email,
            'is_super_admin' => $user->is_super_admin,
            'has_superadmin_role' => $user->hasRole('superadmin')
        ]);
        
        // If user is a super admin, require OTP verification
        if ($user->is_super_admin || $user->hasRole('superadmin')) {
            \Log::info('Super admin login detected, starting OTP flow', [
                'user_id' => $user->id,
                'session_id' => session()->getId()
            ]);
            
            Session::put('otp_user_id', $user->id);
            Session::put('otp_required', true);
            
            \Log::debug('Session data before OTP generation', [
                'otp_user_id' => Session::get('otp_user_id'),
                'otp_required' => Session::get('otp_required'),
                'all_session' => Session::all()
            ]);
            
            $otpService = app(\App\Services\OtpService::class);
            
            try {
                $otp = $otpService->generateAndSendOtp($user);
                \Log::info('OTP generated and sent', ['user_id' => $user->id]);
                
                // Logout the user until OTP is verified
                Auth::logout();
                \Log::info('User logged out for OTP verification', ['user_id' => $user->id]);
                
                // Redirect to OTP verification page
                return redirect()->route('otp.verify')
                    ->with('status', 'Please enter the 6-digit OTP sent to your email address.');
                    
            } catch (\Exception $e) {
                \Log::error('Failed to send OTP', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Failed to send OTP. Please try again later.',
                ]);
            }
        }
        
        // Regenerate the session for non-super admin users
        Session::regenerate();

        // For non-super admin users, redirect to appropriate dashboard
        return $this->redirectToDashboard($user);
    }

    /**
     * Redirect user to appropriate dashboard based on role
     */
    protected function redirectToDashboard($user): RedirectResponse
    {
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('superadmin') || $user->is_super_admin) {
                return redirect()->route('dashboard');
            }
            if ($user->hasRole('company')) {
                return redirect()->route('company.dashboard');
            }
        }
        
        return redirect()->route('company.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
