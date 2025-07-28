<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.company-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'company') {
                return redirect()->intended('/company/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Unauthorized user role.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid login credentials.']);
    }
}
