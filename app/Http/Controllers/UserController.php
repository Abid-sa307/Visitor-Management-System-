<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['company', 'departments'])->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $companies = Company::all();
        $departments = auth()->user()->role === 'superadmin'
            ? Department::all()
            : Department::where('company_id', auth()->user()->company_id)->get();

        return view('users.create', compact('companies', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'company_id' => 'required|exists:companies,id',
            'department_ids' => 'required|array|min:1',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $request->company_id,
        ]);

         $user->departments()->sync($request->department_ids);


        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $companies = Company::all();
        $departments = auth()->user()->role === 'superadmin'
            ? Department::all()
            : Department::where('company_id', auth()->user()->company_id)->get();
        $selectedDepartments = $user->departments->pluck('id')->toArray(); // for edit


        return view('users.edit', compact('user', 'companies', 'departments', 'selectedDepartments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable',
            'role' => 'required',
            'company_id' => 'required|exists:companies,id',
            'department_ids' => 'required|array|min:1',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'role', 'company_id']));
        $user->departments()->sync($request->department_ids);


        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
