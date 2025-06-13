<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function showGuardLogin()
    {
        return view('auth.guard-login');
    }

    public function customLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard'); // Admin dashboard
            } elseif ($user->role === 'guard') {
                return redirect()->route('guard.dashboard'); // Guard dashboard
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or role mismatch.',
        ]);
    }
}
