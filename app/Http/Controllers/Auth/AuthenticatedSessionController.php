<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $user = Auth::user();

            if (method_exists($user, 'hasRole')) {
                if ($user->hasRole('superadmin')) {
                    return redirect()->route('dashboard'); // Redirect superadmin to admin dashboard
                }
                if ($user->hasRole('company')) {
                    return redirect()->route('company.dashboard'); // Redirect company users to company dashboard
                }
            }

            // Fallback (if role is not set or not found)
            return redirect()->route('company.dashboard');
        }

        // Return the login view if the user is not logged in
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        // After login, redirect based on role (superadmin/company)
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('superadmin')) {
                return redirect()->intended(route('dashboard', absolute: false)); // Admin dashboard
            }
            if ($user->hasRole('company')) {
                return redirect()->intended(route('company.dashboard', absolute: false)); // Company dashboard
            }
        }

        // Fallback redirect (in case of unexpected roles)
        return redirect()->intended(route('company.dashboard', absolute: false));
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
