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
        $isCompany = Auth::guard('company')->check();
        $authUser = $isCompany ? Auth::guard('company')->user() : Auth::user();
        $isSuper = $authUser && in_array($authUser->role, ['super_admin', 'superadmin'], true);

        // Base query with relationships
        $employeeQuery = Employee::query()
            ->with(['company', 'department'])
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
        } elseif ($isCompany && $authUser) {
            $companyId = $authUser->company_id;
            $employeeQuery->where('company_id', $companyId);
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
            $branches = \App\Models\Branch::where('company_id', $companyId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
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
            'isCompany' => $isCompany,
            'from' => $fromDate,
            'to' => $toDate,
        ]);
    }

    public function create()
    {
        $companies = Company::all();
        $departments = Department::all();
        return view('employees.create', compact('companies', 'departments'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.regex'  => 'Name may only contain letters, spaces, apostrophes, periods, and hyphens.',
            'phone.regex' => 'Phone must be digits only and can include an optional leading + (7-15 digits).',
        ];

        $validated = $request->validate([
            'company_id'    => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'name'          => 'required|string|max:255|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'\-\.]+$/u',
            'designation'   => 'nullable|string|max:255',
            'email'         => 'nullable|email:rfc,dns',
            'phone'         => 'nullable|regex:/^\+?[0-9]{7,15}$/',
        ], $messages);

        $validated['name'] = Str::squish($validated['name']);
        if (!empty($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $companies = Company::all();
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'companies', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $messages = [
            'name.regex'  => 'Name may only contain letters, spaces, apostrophes, periods, and hyphens.',
            'phone.regex' => 'Phone must be digits only and can include an optional leading + (7-15 digits).',
        ];

        $validated = $request->validate([
            'company_id'    => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'name'          => 'required|string|max:255|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'\-\.]+$/u',
            'designation'   => 'nullable|string|max:255',
            'email'         => 'nullable|email:rfc,dns',
            'phone'         => 'nullable|regex:/^\+?[0-9]{7,15}$/',
        ], $messages);

        $validated['name'] = Str::squish($validated['name']);
        if (!empty($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }
}
