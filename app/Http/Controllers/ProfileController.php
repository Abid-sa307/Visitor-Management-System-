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
        $isCompany = Auth::guard('company')->check();
        $user = $isCompany ? Auth::guard('company')->user() : $request->user();

        // Validate profile update using the correct users table
        $table = $isCompany ? 'company_users' : 'users';
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:' . $table . ',email,' . ($user->id ?? 'NULL') . ',id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route($isCompany ? 'company.profile.edit' : 'profile.edit')
            ->with('status', 'Profile updated successfully');
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
        $isCompany = Auth::guard('company')->check();
        $user = $isCompany ? Auth::guard('company')->user() : $request->user();

        // Validate password for deletion
        $request->validate([
            'password' => 'required|current_password',
        ]);

        // Perform deletion
        $user->delete();

        // Logout and invalidate session
        $isCompany ? Auth::guard('company')->logout() : Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
