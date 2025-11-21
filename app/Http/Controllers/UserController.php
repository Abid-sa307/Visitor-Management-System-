<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyUser;
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

        $users = User::with([
                'company:id,name',
                'departments:id,name',
                'department:id,name',   // if you keep department_id
            ])
            ->when(!in_array($authUser->role, ['super_admin','superadmin'], true), function ($q) use ($authUser) {
                $q->where('company_id', $authUser->company_id);
            })
            ->latest()
            ->paginate(15);

        $view = $isCompany ? 'company.users.index' : 'users.index';
        if (!view()->exists($view)) $view = 'users.index';

        return view($view, compact('users'));
    }

    public function create()
    {
        $auth    = Auth::user();
        $isSuper = in_array($auth->role, ['super_admin','superadmin'], true);

        $companies = $isSuper
            ? Company::select('id','name')->orderBy('name')->get()
            : collect();

        $user = new User();

        return view('users.create', compact('companies','user'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        $rules = [
            'name'        => ['required','string','max:255'],
            'email'       => ['required','email','unique:users,email'],
            'password'    => ['required','string','min:6','confirmed'],
            'phone'       => ['nullable','string','max:30'],
            'master_pages'=> ['array'],
            'master_pages.*'=> ['string'],
            // accept either departments[] or department_ids[]
            'departments'     => ['array'],
            'departments.*'   => ['exists:departments,id'],
            'department_ids'  => ['array'],
            'department_ids.*'=> ['exists:departments,id'],
            'branch_id'       => ['nullable','exists:branches,id'],
        ];

        if (in_array($authUser->role, ['super_admin','superadmin'], true)) {
            $rules['company_id'] = ['required','exists:companies,id'];
        }

        $data = $request->validate($rules);

        $user = new User();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->phone    = $data['phone'] ?? null;
        $user->role     = $request->role; // e.g. superadmin/company/employee

        // company assignment
        if (in_array($authUser->role, ['company','company_user'], true)) {
            $user->company_id = $authUser->company_id;
        } else {
            $user->company_id = $data['company_id'] ?? null;
        }

        // branch (optional)
        $user->branch_id = $request->input('branch_id');

        // page access (array cast on model)
        $user->master_pages = $data['master_pages'] ?? [];
        $user->save();

        // Sync to company_users for company guard login
        if (in_array(($request->role ?? ''), ['company','company_user'], true)) {
            $payload = [
                'name' => $user->name,
                // Assign plain password; CompanyUser casts will hash automatically
                'password' => $data['password'],
                'company_id' => $user->company_id,
                'role' => 'company',
            ];
            // mirror branch_id if the column exists in company_users
            try {
                if (\Schema::hasColumn('company_users','branch_id')) {
                    $payload['branch_id'] = $user->branch_id;
                }
            } catch (\Throwable $e) {}

            CompanyUser::updateOrCreate(
                ['email' => $user->email],
                $payload
            );
        }

        // departments pivot (support both field names)
        $deptIds = $request->input('department_ids', $request->input('departments', []));
        if (!empty($deptIds)) {
            $user->departments()->sync($deptIds);
        }

        return redirect()->route('users.index')->with('success','User created successfully.');
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

    public function update(Request $request, User $user)
    {
        $auth = auth()->user();
        $isSuper = in_array($auth->role, ['super_admin','superadmin'], true);

        if (!$isSuper && $user->company_id !== $auth->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'email'       => ['required','email','unique:users,email,'.$user->id],
            'password'    => ['nullable','string','min:6','confirmed'],
            'phone'       => ['nullable','string','max:30'],
            'master_pages'=> ['array'],
            'master_pages.*'=> ['string'],
            'departments'     => ['array'],
            'departments.*'   => ['exists:departments,id'],
            'department_ids'  => ['array'],
            'department_ids.*'=> ['exists:departments,id'],
            'branch_id'       => ['nullable','exists:branches,id'],
            // 'company_id' => $isSuper ? ['required','exists:companies,id'] : ['nullable'],
        ]);

        $user->fill([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (array_key_exists('master_pages', $data)) {
            $user->master_pages = $data['master_pages'] ?? [];
        }

        // update branch if provided
        if ($request->has('branch_id')) {
            $user->branch_id = $request->input('branch_id') ?: null;
        }

        // if ($isSuper && array_key_exists('company_id', $data)) {
        //     $user->company_id = $data['company_id'];
        // }

        $user->save();

        // departments pivot
        if ($request->has('department_ids') || $request->has('departments')) {
            $deptIds = $request->input('department_ids', $request->input('departments', []));
            $user->departments()->sync($deptIds ?: []);
        }

        // mirror to company_users if role is company/company_user
        if (in_array(($user->role ?? ''), ['company','company_user'], true)) {
            $payload = [
                'name' => $user->name,
                'company_id' => $user->company_id,
                'role' => 'company',
            ];
            try {
                if (\Schema::hasColumn('company_users','branch_id')) {
                    $payload['branch_id'] = $user->branch_id;
                }
            } catch (\Throwable $e) {}
            CompanyUser::updateOrCreate(
                ['email' => $user->email],
                $payload
            );
        }

        return redirect()->route('users.index')->with('success','User updated successfully.');
    }

    public function destroy(User $user)
    {
        $auth = Auth::user();

        // Only super admins can delete (adjust if you want company admins to delete within their company)
        if (!in_array($auth->role, ['super_admin','superadmin'], true)) {
            abort(403, 'Unauthorized');
        }

        // Prevent deleting super admins (optional safety)
        if (in_array($user->role, ['super_admin','superadmin'], true)) {
            return redirect()->route('users.index')->with('error', 'Cannot delete a super admin user.');
        }

        // detach pivots first (keeps pivot tables clean)
        if (method_exists($user, 'departments')) {
            $user->departments()->detach();
        }
        // If you ever add a pages pivot, detach here as well
        // if (method_exists($user, 'pages')) { $user->pages()->detach(); }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
