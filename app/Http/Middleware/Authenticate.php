<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
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
        // List of routes that should be accessible without authentication
        $publicRoutes = [
            'public.visitor.show',
            'public.visitor.visit.form',
            'public.visitor.visit.form.branch',
            'public.visitor.visit.store',
            'public.visitor.visit.store.branch',
            'public.visitor.visit.edit',
            'public.visitor.visit.edit.branch',
            'public.visitor.track',
            'qr.scan',
            'visitors.pass',
            'visitors.pass.pdf',
        ];

        // Check if current route is in the public routes list
        $routeName = $request->route() ? $request->route()->getName() : 'no-route';
        
        if (in_array($routeName, $publicRoutes)) {
            // This is a public route, allow access without authentication
            return $next($request);
        }

        // For all other routes, require authentication
        if (Auth::check()) {
            return $next($request);
        }

        // Redirect to login if not authenticated
        return redirect()->route('login');
    }
}
