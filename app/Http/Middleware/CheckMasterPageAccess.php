<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMasterPageAccess
{
    public function handle(Request $request, Closure $next, $page)
    {
        $user = auth()->user();

        // Handle null case
        if (!$user || !$user->master_pages) {
            abort(403, 'Unauthorized.');
        }

        $pages = json_decode($user->master_pages, true); // decode json

        // Block if page is not in allowed list
        if (!is_array($pages) || !in_array($page, $pages)) {
            abort(403, 'Access Denied!');
        }

        return $next($request);
    }
}
