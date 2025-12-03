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

        // Debug logging
        \Log::info('CheckMasterPageAccess - User:', [
            'id' => $user ? $user->id : null,
            'email' => $user ? $user->email : null,
            'role' => $user ? $user->role : null,
            'master_pages' => $user ? $user->master_pages : null,
            'route' => $request->route() ? $request->route()->getName() : null,
            'page' => $page
        ]);

        // Ensure user is logged in
        if (!$user) {
            \Log::warning('CheckMasterPageAccess - No authenticated user');
            return redirect()->route('login');
        }

        $role = (string)($user->role ?? '');

        // Allow superadmins to access anything
        if (in_array($role, ['super_admin', 'superadmin'], true)) {
            \Log::info('CheckMasterPageAccess - Superadmin access granted');
            return $next($request);
        }
        
        // For QR scanner, check if user has either qr_scanner or qr_code permission
        if ($request->routeIs('company.qr.scanner') || $request->routeIs('company.qr.scan') || 
            $request->routeIs('qr.scanner') || $request->routeIs('qr.scan')) {
                
            $masterPages = is_array($user->master_pages) ? $user->master_pages : json_decode($user->master_pages, true) ?? [];
            $hasPermission = in_array('qr_scanner', $masterPages, true) || 
                           in_array('qr_code', $masterPages, true);
                           
            \Log::info('CheckMasterPageAccess - QR Scanner access check', [
                'has_permission' => $hasPermission,
                'master_pages' => $masterPages,
                'route' => $request->route()->getName()
            ]);
            
            if ($hasPermission) {
                return $next($request);
            }
            
            // If no permission, show 403
            \Log::warning('CheckMasterPageAccess - Access denied to QR Scanner', [
                'user_id' => $user->id,
                'master_pages' => $masterPages,
                'route' => $request->route()->getName()
            ]);
            
            abort(403, 'You do not have permission to access the QR Scanner.');
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
                    'qr-code.*' => 'qr_code',
                    'qr.scanner' => 'qr_scanner',
                    'qr.scan' => 'qr_scanner'
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
