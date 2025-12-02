<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMasterPageAccess
{
    public function handle(Request $request, Closure $next, ?string $page = null)
    {
        // Prefer company guard user when inside company panel
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : $request->user();

        // Ensure user is logged in
        if (!$user) {
            return redirect()->route('login');
        }

        $role = (string)($user->role ?? '');

        // Allow superadmins to access anything
        if (in_array($role, ['super_admin', 'superadmin'], true)) {
            return $next($request); // Superadmin can access anything
        }

        // If no page is provided, try to get it from the route
        if (!$page) {
            $page = $request->route()->defaults['page'] ?? null;
            // If still no page, try to determine from the route name
            if (!$page) {
                $routeName = $request->route()->getName();
                // Map route names to page keys
                $routeToPageMap = [
                    'dashboard' => 'dashboard',
                    'visitors.index' => 'visitors',
                    'visitors.history' => 'visitor_history',
                    'visitors.inout' => 'visitor_inout',
                    'approvals.index' => 'approvals',
                    'reports.*' => 'reports',
                    'employees.*' => 'employees',
                    'visitor-categories.*' => 'visitor_categories',
                    'departments.*' => 'departments',
                    'users.*' => 'users',
                    'security-checks.*' => 'security_checks',
                    'visitor-checkup.*' => 'visitor_checkup',
                    'qr-code.*' => 'qr_code'
                ];

                foreach ($routeToPageMap as $routePattern => $pageKey) {
                    if (str_is($routePattern, $routeName)) {
                        $page = $pageKey;
                        break;
                    }
                }
            }
        }

        // If still no specific page to check, proceed
        if (!$page) {
            return $next($request);
        }

        // For company users, check if they have access to the requested page
        if ($role === 'company') {
            $pages = $user->master_pages ?? [];
            if (!is_array($pages)) {
                $pages = is_string($pages) && $pages !== '' ? json_decode($pages, true) : [];
            }

            // Special case: company users should have access to their own company dashboard
            if ($page === 'dashboard' && $request->is('company/dashboard*')) {
                return $next($request);
            }

            // Check if the user has access to the requested page
            if (!empty($pages) && !in_array($page, $pages, true)) {
                return redirect('/company/dashboard')->with('error', 'Access denied for this section.');
            }

            return $next($request);
        }

        // For other users, check master pages access
        $pages = $user->master_pages ?? [];
        if (!is_array($pages)) {
            $pages = is_string($pages) && $pages !== '' ? json_decode($pages, true) : [];
        }

        // If the user doesn't have access to the requested page, redirect to dashboard
        if (!empty($pages) && !in_array($page, $pages, true)) {
            return redirect('/dashboard')->with('error', 'Access denied for this section.');
        }

        return $next($request);
    }
}
