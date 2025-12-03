<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyLoginController extends Controller
{
    /**
     * Show the company login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.company.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Debug: Check if user exists
        $user = \App\Models\CompanyUser::where('email', $credentials['email'])->first();
        
        \Log::info('Login attempt:', [
            'email' => $credentials['email'],
            'user_exists' => $user ? 'Yes' : 'No',
            'user_id' => $user ? $user->id : null,
            'password_matches' => $user ? (Hash::check($credentials['password'], $user->password) ? 'Yes' : 'No') : 'N/A',
            'stored_password' => $user ? $user->password : 'N/A',
            'input_password' => $credentials['password'],
            'hashed_input' => Hash::make($credentials['password']),
        ]);
        
        if ($user) {
            if (!Hash::check($credentials['password'], $user->password)) {
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }
            
            // Manually log in the user
            if (Auth::guard('company')->login($user, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
