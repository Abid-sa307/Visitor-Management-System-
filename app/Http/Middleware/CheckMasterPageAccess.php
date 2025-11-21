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

        // If user is company, skip master_pages check (company users manage their own panel)
        if ($role === 'company') {
            return $next($request);
        }

        // If no page is provided, proceed normally
        if (!$page) {
            $page = $request->route()->defaults['page'] ?? null;
        }

        if (!$page) return $next($request); // If no specific page, proceed normally

        // Decode master_pages and check for access
        $pages = $user->master_pages;
        if (!is_array($pages)) {
            $pages = is_string($pages) && $pages !== '' ? json_decode($pages, true) : [];
        }

        // If the user does not have access to this page, redirect to the correct dashboard
        if (!in_array($page, $pages, true)) {
            if ($role === 'company') {
                return redirect('/company/dashboard')->with('error', 'Access denied for this section.');
            } else {
                return redirect('/dashboard')->with('error', 'Access denied for this section.');
            }
        }

        return $next($request);
    }
}
