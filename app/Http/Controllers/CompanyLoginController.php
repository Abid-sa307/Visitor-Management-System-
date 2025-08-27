<?php

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

        if (Auth::attempt($credentials)) {
            if (auth()->user()->role === 'company') {
                return redirect()->route('company.dashboard');
            } else {
                Auth::logout();
                return back()->with('error', 'Access denied. You are not a company user.');
            }
        }

        return back()->with('error', 'Invalid email or password.');
    }
}
