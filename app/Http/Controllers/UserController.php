<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    public function index()
    {
        $authUser = auth()->user();

        // If superadmin is logged in -> show all users
        if ($authUser->role === 'superadmin') {
            $users = User::with(['company', 'departments'])->get();
        } 
        else {
            // Company users should only see their own company's users
            $users = User::with(['company', 'departments'])
                ->where('company_id', $authUser->company_id)
                ->where('role', '!=', 'superadmin') // exclude superadmin
                ->get();
        }
         $users = User::with(['company', 'departments'])
            ->visibleTo(auth()->user())   // 👈 scope added here
            ->get();

        return view('company.users.index', compact('users'));
    }

    public function create()
    {
        $authUser = Auth::user();

        // Company user can only create for their company
        if ($authUser->role === 'company') {
            return view('users.create')->with('company_id', $authUser->company_id);
        }

        return view('users.create')->with('company_id', null);
    }

    public function store(Request $request)
    {
        $authUser = Auth::user();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->password = Hash::make($validated['password']);

        // Force company_id if logged in as company user
        if ($authUser->role === 'company') {
            $user->company_id = $authUser->company_id;
        } else {
            $user->company_id = $request->company_id ?? null;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $authUser = Auth::user();

        // Company user can only edit their own company’s users
        if ($authUser->role === 'company' && $user->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();

        // Prevent editing users outside company
        if ($authUser->role === 'company' && $user->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6|confirmed',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $authUser = Auth::user();

        // Prevent deleting users outside company
        if ($authUser->role === 'company' && $user->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
