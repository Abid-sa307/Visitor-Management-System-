<?php
// app/Http/Controllers/Auth/CompanyLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.company.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('company')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('company')->user();
            if (($user->role ?? null) === 'company') {
                return redirect()->route('company.dashboard');
            }
            Auth::guard('company')->logout();
            return back()->with('error', 'Access denied. Not a company user.');
        }

        return back()->with('error', 'Invalid credentials.');
    }
}
