<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Middleware\CheckMasterPageAccess;
use App\Http\Middleware\RoleMiddleware;

class ProfileController extends Controller
{
    /**
     * Display the profile edit form for company users.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the profile for company users.
     */
    public function update(Request $request)
    {
        // Validate profile update
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;

        // If the password is set, update it
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($request->user()->hasRole('company')) {
            return redirect()->route('company.profile.edit')->with('status', 'Profile updated successfully');
        } else {
            return redirect()->route('profile.edit')->with('status', 'Profile updated successfully');
        }
    }

    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:company|superadmin'); // Keep this as it is to restrict middleware access
    // }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // Validate password for deletion
        $request->validate([
            'password' => 'required|current_password',
        ]);

        // Perform deletion
        $user->delete();

        // Logout and invalidate session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
