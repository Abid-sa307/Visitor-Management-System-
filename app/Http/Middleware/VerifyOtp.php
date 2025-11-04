<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip for non-authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Skip OTP check for non-superadmin users
        if (!($user->is_super_admin || $user->hasRole('superadmin'))) {
            return $next($request);
        }

        // Get the current route name
        $routeName = $request->route() ? $request->route()->getName() : null;
        
        // Allow access to OTP verification routes and logout
        $allowedRoutes = ['otp.verify', 'otp.verify.post', 'otp.resend', 'logout', 'login'];
        
        if (in_array($routeName, $allowedRoutes)) {
            return $next($request);
        }

        // Check if OTP is required and not yet verified
        if (!Session::get('otp_verified', false)) {
            // Store the intended URL for after OTP verification
            if (!in_array($routeName, ['otp.verify', 'otp.verify.post'])) {
                Session::put('url.intended', $request->fullUrl());
            }
            
            // Make sure we don't get stuck in a redirect loop
            if ($routeName !== 'otp.verify') {
                return redirect()->route('otp.verify')
                    ->with('status', 'Please verify your OTP to continue.');
            }
        }

        return $next($request);
    }
}
