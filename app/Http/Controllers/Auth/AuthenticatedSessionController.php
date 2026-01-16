<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

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
        
        // Authenticate the user
        if (!Auth::attempt($credentials, $remember)) {
            \Log::warning('Login failed: Invalid credentials', ['email' => $email]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        
        // Get the authenticated user
        $user = Auth::user();
        
        // Check if this is a superadmin login attempt
        if ($request->routeIs('superadmin.login.store')) {
            // Only allow superadmin users to login via superadmin route
            if (!($user->hasRole('superadmin') || $user->is_super_admin)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. This login is for administrators only.',
                ]);
            }
        }

\Log::info('DEBUG SUPERADMIN CHECK', [
    'user_id'         => $user->id,
    'email'           => $user->email,
    'is_super_admin'  => $user->is_super_admin,
    'has_superadmin'  => method_exists($user, 'hasRole') ? $user->hasRole('superadmin') : null,
    'all_roles'       => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : null,
]);

        
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
        
        // Check if user is super admin and requires OTP verification
        if (($user->hasRole('superadmin') || $user->is_super_admin) && !Session::get('otp_verified', false)) {
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
        $request->session()->regenerate();
        
        // Log the successful login
        \Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_super_admin' => $user->is_super_admin
        ]);
        
        // Redirect to the appropriate dashboard
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
