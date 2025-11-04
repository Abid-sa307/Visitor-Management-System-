<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['company', 'department'])->latest()->paginate(10);
        return view('employees.index', compact('employees'));
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
