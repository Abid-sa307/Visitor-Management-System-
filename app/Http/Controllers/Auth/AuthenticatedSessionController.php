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
        
        // Authenticate the user
        if (!Auth::attempt($credentials, $remember)) {
            \Log::warning('Login failed: Invalid credentials', ['email' => $email]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        
        // Get the authenticated user
        $user = Auth::user();

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
        
        // Skip OTP verification for all users and log them in directly
        $request->session()->regenerate();
        
        // Log the successful login
        \Log::info('User logged in successfully (OTP bypassed)', [
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
