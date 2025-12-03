<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class VerifyOtp
{
    public function handle(Request $request, Closure $next)
    {
        // Skip OTP verification for all users
        return $next($request);
    }
}