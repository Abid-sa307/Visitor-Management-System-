<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the profile edit form for company users.
     */
    public function edit(Request $request)
    {
        // Only allow company users to access this route
        if ($request->user()->role !== 'company') {
            abort(403);
        }

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

        return Redirect::route('company.profile.edit')->with('status', 'Profile updated successfully');
    }

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
