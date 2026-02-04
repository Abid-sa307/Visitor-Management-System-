<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Department;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Get authenticated user (company routes use 'auth' middleware with web guard)
        $authUser = auth()->user();
        $isSuper = $authUser && in_array($authUser->role, ['super_admin', 'superadmin'], true);

        // Base query with relationships
        $employeeQuery = Employee::query()
            ->with(['company', 'department', 'branch', 'departments'])
            ->latest('created_at');

        // Apply date range filter if provided
        $fromDate = $request->input('from') ?: now()->subDays(30)->format('Y-m-d');
        $toDate = $request->input('to') ?: now()->format('Y-m-d');
        
        $employeeQuery->whereDate('created_at', '>=', $fromDate)
                     ->whereDate('created_at', '<=', $toDate);

        // Company filter (for superadmin)
        $companyId = null;
        if ($isSuper) {
            $companyId = $request->input('company_id');
            if ($companyId) {
                $employeeQuery->where('company_id', $companyId);
            }
        } else {
            // Company users - filter by their company
            $companyId = $authUser->company_id;
            $employeeQuery->where('company_id', $companyId);
            
            // Filter by user's assigned branches
            $userBranchIds = $authUser->branches()->pluck('branches.id')->toArray();
            \Log::info('Employee Index - Branch Filter', [
                'user_id' => $authUser->id,
                'user_role' => $authUser->role,
                'company_id' => $companyId,
                'branch_ids' => $userBranchIds
            ]);
            if (!empty($userBranchIds)) {
                $employeeQuery->whereIn('branch_id', $userBranchIds);
            }
        }

        // Department filter
        if ($request->filled('department_id')) {
            $employeeQuery->where('department_id', $request->input('department_id'));
        }

        $employees = $employeeQuery->paginate(10)->appends($request->query());

        // Get companies for superadmin dropdown
        $companies = [];
        if ($isSuper) {
            $companies = \App\Models\Company::orderBy('name')->pluck('name', 'id')->toArray();
        }

        // Get branches based on company selection
        $branches = [];
        if ($companyId) {
            if ($isSuper) {
                // Superadmin sees all branches
                $branches = \App\Models\Branch::where('company_id', $companyId)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->toArray();
            } else {
                // Company users see only their assigned branches
                $branches = $authUser->branches()->orderBy('name')->pluck('name', 'branches.id')->toArray();
            }
        }

        // Get departments based on company selection
        $departments = [];
        if ($companyId) {
            $departments = \App\Models\Department::where('company_id', $companyId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        return view('employees.index', [
            'employees' => $employees,
            'departments' => $departments,
            'branches' => $branches,
            'companies' => $companies,
            'isSuper' => $isSuper,
            'from' => $fromDate,
            'to' => $toDate,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $isSuper = in_array($user->role, ['super_admin', 'superadmin'], true);
        
        if ($isSuper) {
            $companies = Company::pluck('name', 'id');
            $branches = [];
            $departments = [];
        } else {
            $companies = [$user->company_id => $user->company->name];
            // Get only branches assigned to this user
            $branches = $user->branches()->orderBy('name')->pluck('name', 'branches.id');
            $departments = [];
        }
        
        return view('employees.create', compact('companies', 'branches', 'departments', 'isSuper'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.regex'  => 'Name may only contain letters, spaces, apostrophes, periods, and hyphens.',
            'phone.regex' => 'Phone must be digits only and can include an optional leading + (7-15 digits).',
        ];

        $validated = $request->validate([
            'company_id'     => 'required|exists:companies,id',
            'branch_id'      => 'nullable|exists:branches,id',
            'department_id'  => 'nullable|exists:departments,id',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
            'name'           => 'required|string|max:255|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'\-\.]+$/u',
            'designation'    => 'nullable|string|max:255',
            'email'          => 'nullable|email:rfc,dns',
            'phone'          => 'nullable|regex:/^\+?[0-9]{7,15}$/',
        ], $messages);

        $validated['name'] = Str::squish($validated['name']);
        if (!empty($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        $employee = Employee::create($validated);
        
        if (!empty($validated['department_ids'])) {
            $employee->departments()->sync($validated['department_ids']);
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $user = auth()->user();
        $isSuper = in_array($user->role, ['super_admin', 'superadmin'], true);
        
        if ($isSuper) {
            $companies = Company::pluck('name', 'id');
            $branches = Branch::where('company_id', $employee->company_id)->pluck('name', 'id');
            $departments = Department::where('branch_id', $employee->branch_id)->pluck('name', 'id');
        } else {
            $companies = [$employee->company_id => $employee->company->name];
            // Get only branches assigned to this user
            $branches = $user->branches()->orderBy('name')->pluck('name', 'branches.id');
            $departments = Department::where('branch_id', $employee->branch_id)->pluck('name', 'id');
        }
        
        $employee->load('departments');
        return view('employees.edit', compact('employee', 'companies', 'branches', 'departments', 'isSuper'));
    }

    public function update(Request $request, Employee $employee)
    {
        $messages = [
            'name.regex'  => 'Name may only contain letters, spaces, apostrophes, periods, and hyphens.',
            'phone.regex' => 'Phone must be digits only and can include an optional leading + (7-15 digits).',
        ];

        $validated = $request->validate([
            'company_id'     => 'required|exists:companies,id',
            'branch_id'      => 'nullable|exists:branches,id',
            'department_id'  => 'nullable|exists:departments,id',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
            'name'           => 'required|string|max:255|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'\-\.]+$/u',
            'designation'    => 'nullable|string|max:255',
            'email'          => 'nullable|email:rfc,dns',
            'phone'          => 'nullable|regex:/^\+?[0-9]{7,15}$/',
        ], $messages);

        $validated['name'] = Str::squish($validated['name']);
        if (!empty($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        $employee->update($validated);
        
        if (isset($validated['department_ids'])) {
            $employee->departments()->sync($validated['department_ids']);
        } else {
            $employee->departments()->detach();
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }
}
