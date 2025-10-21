<?php

// RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
        public function handle($request, Closure $next, ...$roles)
        {
            // Try default guard first
            $user = Auth::user();

            // Fallback to company guard
            if (!$user && Auth::guard('company')->check()) {
                $user = Auth::guard('company')->user();
            }

            // Fallback to security guard (if used)
            if (!$user && Auth::guard('guard')->check()) {
                $user = Auth::guard('guard')->user();
            }

            if (!$user || !in_array($user->role, $roles)) {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        }
}
