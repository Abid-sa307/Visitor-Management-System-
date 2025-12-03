<?php
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

        if (!$user) {
            abort(403, 'Unauthenticated.');
        }

        // Check if user has any of the required roles
        $hasRole = false;

        // Check if user has role attribute directly
        if (isset($user->role) && in_array($user->role, $roles)) {
            $hasRole = true;
        } 
        // Fallback to hasRole method if available (from Spatie permissions)
        elseif (method_exists($user, 'hasRole')) {
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $hasRole = true;
                    break;
                }
            }
        }

        if (!$hasRole) {
            abort(403, 'Unauthorized. You do not have the required role.');
        }

        return $next($request);
    }
}
