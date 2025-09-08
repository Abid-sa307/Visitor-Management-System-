<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** Quick helper so we survive mixed role naming across the app */
    private function roleIs($user, ...$roles): bool
    {
        $current = (string) ($user->role ?? '');
        // normalize: superadmin ↔ super_admin, company ↔ company_user
        $aliases = [
            'superadmin'    => 'super_admin',
            'super_admin'   => 'super_admin',
            'company'       => 'company_user',
            'company_user'  => 'company_user',
        ];
        $current = $aliases[$current] ?? $current;

        foreach ($roles as $r) {
            $r = $aliases[$r] ?? $r;
            if ($current === $r) return true;
        }
        return false;
    }

   public function index(Request $request)
{
    $authUser  = auth()->user();
    $isCompany = $request->is('company/*');

    // ✅ Build relations array dynamically
    $relations = ['company'];
    if (method_exists(User::class, 'departments')) $relations[] = 'departments';
    if (method_exists(User::class, 'department'))  $relations[] = 'department';

    $query = User::with($relations);

    if ($authUser->role === 'super_admin' || $authUser->role === 'superadmin') {
        // show all
    } else {
        $query->where('company_id', $authUser->company_id);
    }

    $users = $query->paginate(15);

    $view = $isCompany ? 'company.users.index' : 'users.index';
    if (!view()->exists($view)) $view = 'users.index';

    return view($view, compact('users'));
}


    public function create()
    {
        $auth = Auth::user();
        $isSuper = in_array($auth->role, ['super_admin','superadmin'], true);

        $companies = $isSuper
            ? Company::select('id','name')->orderBy('name')->get()
            : collect(); // empty collection for company users

        // For the form partial: create mode has no $user yet
        $user = new User();

        return view('users.create', compact('companies','user'));
    }

    public function edit(User $user)
    {
        $auth = Auth::user();
        $isSuper = in_array($auth->role, ['super_admin','superadmin'], true);

        if (!$isSuper && $user->company_id !== $auth->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $companies = $isSuper
            ? Company::select('id','name')->orderBy('name')->get()
            : collect();

        return view('users.edit', compact('user','companies'));
    }

    public function store(Request $request)
{
    $authUser = Auth::user();

    $validated = $request->validate([
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|unique:users,email',
        'password'  => 'required|string|min:6|confirmed',
        'company_id' => 'required|exists:companies,id'  // Ensure company_id is validated
    ]);

    $user = new User();
    $user->name     = $validated['name'];
    $user->email    = $validated['email'];
    $user->password = Hash::make($validated['password']);
    
    // If the user is a company user, assign their company_id.
    if ($authUser->role === 'company') {
        $user->company_id = $authUser->company_id;
    } else {
        // Ensure the selected company ID is assigned
        $user->company_id = $validated['company_id'];
    }

    $user->role = $request->role; // Assign the role as per the form

    $user->save();

    return redirect()->route('users.index')->with('success', 'User created successfully.');
}


    public function update(Request $request, User $user)
    {
        $auth = Auth::user();
        $isSuper = in_array($auth->role, ['super_admin','superadmin'], true);

        if (!$isSuper && $user->company_id !== $auth->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            // if you allow superadmin to move users across companies, uncomment:
            // 'company_id' => $isSuper ? 'required|exists:companies,id' : 'nullable',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        // if moving companies is allowed:
        // if ($isSuper) $user->company_id = $validated['company_id'];

        $user->save();

        return redirect()->route('users.index')->with('success','User updated successfully.');
    }

    public function destroy(User $user)
    {
        $authUser = Auth::user();

        if ($this->roleIs($authUser, 'company_user', 'company') && $user->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
