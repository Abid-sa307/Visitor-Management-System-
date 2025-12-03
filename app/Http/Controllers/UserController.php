<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
    $isCompany = auth()->guard('company')->check();
    $isSuper = auth()->user()->isSuperAdmin();

    // Get companies for the filter (only for super admins)
    $companies = [];
    if ($isSuper) {
        $companies = Company::orderBy('name')->pluck('name', 'id')->toArray();
    }

    $query = User::query()->with(['company', 'departments']);

    // Apply search
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Rest of your existing filters...
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->input('company_id'));
    }

    if ($request->filled('status')) {
        $query->where('is_active', $request->input('status') === 'active');
    }

    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('created_at', [
            $request->input('from'),
            \Carbon\Carbon::parse($request->input('to'))->endOfDay()
        ]);
    }

    $users = $query->latest()->paginate(15);

    return view('users.index', [
        'users' => $users,
        'companies' => $companies,
        'isSuper' => $isSuper,
        'isCompany' => $isCompany,
        'from' => $request->input('from', now()->subDays(30)->format('Y-m-d')),
        'to' => $request->input('to', now()->format('Y-m-d')),
    ]);
}

    public function create()
    {
        $auth = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        if (!$auth) {
            return redirect()->route('login');
        }

        $isSuper = in_array($auth->role, ['super_admin','superadmin'], true);

        // If user is a company user, get their company
        $companies = $isSuper
            ? Company::select('id','name')->orderBy('name')->get()
            : collect([
                Company::select('id','name')
                    ->where('id', $auth->company_id)
                    ->first()
            ])->filter();

        $user = new User();
        
        // If user is a company user, set the company_id for the new user
        if (!$isSuper && $auth->company_id) {
            $user->company_id = $auth->company_id;
        }

        return view('users.create', compact('companies', 'user'));
    }

    public function store(Request $request)
    {
        // Get the authenticated user, checking both guards
        $authUser = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        if (!$authUser) {
            return redirect()->route('login');
        }

        $isSuper = in_array($authUser->role, ['super_admin','superadmin'], true);

        $rules = [
            'name'        => ['required','string','max:255','regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'\-\.]+$/u'],
            'email'       => ['required','email:rfc,dns','unique:users,email'],
            'password'    => ['required','string','min:8','confirmed'],
            'phone'       => ['nullable','regex:/^\+?[0-9]{7,15}$/'],
            'master_pages'=> ['array'],
            'master_pages.*'=> ['string'],
            // accept either departments[] or department_ids[]
            'departments'     => ['array'],
            'departments.*'   => ['exists:departments,id'],
            'department_ids'  => ['array'],
            'department_ids.*'=> ['exists:departments,id'],
            'branch_id'       => ['nullable','exists:branches,id'],
        ];

        // Only super admins can set company_id
        if ($isSuper) {
            $rules['company_id'] = ['required','exists:companies,id'];
            $rules['role'] = ['required', 'string', 'in:superadmin,admin,company,employee'];
        } else {
            // For company users, set default role to 'employee' if not provided
            $request->merge(['role' => $request->input('role', 'employee')]);
        }

        $messages = [
            'name.regex'   => 'Name may only contain letters, spaces, apostrophes, periods, and hyphens.',
            'phone.regex'  => 'Phone must be digits only and can include an optional leading + (7-15 digits).',
        ];

        $data = $request->validate($rules, $messages);

        $user = new User();
        $user->name     = Str::squish($data['name']);
        $user->email    = strtolower($data['email']);
        $user->password = Hash::make($data['password']);
        $user->phone    = $data['phone'] ?? null;
        $user->role     = $request->role; // e.g. superadmin/company/employee

        // Set company_id based on user role
        if ($isSuper) {
            $user->company_id = $data['company_id'] ?? null;
        } else {
            // For company users, use their company_id
            $user->company_id = $authUser->company_id;
        }

        // branch (optional)
        $user->branch_id = $request->input('branch_id');

        // page access (array cast on model)
        $user->master_pages = $data['master_pages'] ?? [];
        $user->save();

        // Sync to company_users for company guard login if role is company
        if (in_array(($request->role ?? ''), ['company','company_user'], true)) {
            try {
                $payload = [
                    'name' => $user->name,
                    'email' => $user->email, // Make sure email is included
                    'password' => Hash::make($data['password']), // Explicitly hash the password
                    'company_id' => $user->company_id,
                    'role' => 'company',
                    'master_pages' => $user->master_pages ?? [],
                ];

                // mirror branch_id if the column exists in company_users
                if (\Schema::hasColumn('company_users', 'branch_id')) {
                    $payload['branch_id'] = $user->branch_id;
                }

                CompanyUser::updateOrCreate(
                    ['email' => $user->email],
                    $payload
                );
            } catch (\Exception $e) {
                // Log the error but don't fail the entire operation
                \Log::error('Failed to sync user to company_users table: ' . $e->getMessage());
            }
        }

        // Handle department assignments
        $deptIds = $request->input('department_ids', $request->input('departments', ''));
        
        // Convert to array if it's a string
        if (is_string($deptIds) && !empty($deptIds)) {
            $deptIds = explode(',', $deptIds);
        } elseif (empty($deptIds)) {
            $deptIds = [];
        }
        
        // Ensure we have an array of integers
        $deptIds = array_filter(array_map('intval', (array)$deptIds));
        
        // Sync departments
        $user->departments()->sync($deptIds);

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

        $rules = [
            'name'        => ['required','string','max:255','regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'\-\.]+$/u'],
            'email'       => ['required','email:rfc,dns','unique:users,email,'.$user->id],
            'password'    => ['nullable','string','min:8','confirmed'],
            'phone'       => ['nullable','regex:/^\+?[0-9]{7,15}$/'],
            'master_pages'=> ['array'],
            'master_pages.*'=> ['string'],
            'departments'     => ['array'],
            'departments.*'   => ['exists:departments,id'],
            'department_ids'  => ['array'],
            'department_ids.*'=> ['exists:departments,id'],
            'branch_id'       => ['nullable','exists:branches,id'],
            // 'company_id' => $isSuper ? ['required','exists:companies,id'] : ['nullable'],
        ];

        $messages = [
            'name.regex'  => 'Name may only contain letters, spaces, apostrophes, periods, and hyphens.',
            'phone.regex' => 'Phone must be digits only and can include an optional leading + (7-15 digits).',
        ];

        $data = $request->validate($rules, $messages);

        $user->fill([
            'name'  => Str::squish($data['name']),
            'email' => strtolower($data['email']),
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

        // Handle department assignments
        $deptIds = $request->input('department_ids', $request->input('departments', ''));
        
        // Convert to array if it's a string
        if (is_string($deptIds) && !empty($deptIds)) {
            $deptIds = explode(',', $deptIds);
        } elseif (empty($deptIds)) {
            $deptIds = [];
        }
        
        // Ensure we have an array of integers
        $deptIds = array_filter(array_map('intval', (array)$deptIds));
        
        // Sync departments
        $user->departments()->sync($deptIds);

        // mirror to company_users if role is company/company_user
        if (in_array(($user->role ?? ''), ['company','company_user'], true)) {
            $payload = [
                'name' => $user->name,
                'company_id' => $user->company_id,
                'role' => 'company',
                'master_pages' => $user->master_pages ?? [],
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
