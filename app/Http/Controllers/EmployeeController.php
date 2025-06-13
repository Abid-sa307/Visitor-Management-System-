<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'company_id'    => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'name'          => 'required|string|max:255',
            'designation'   => 'nullable|string|max:255',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string|max:20',
        ]);

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
        $validated = $request->validate([
            'company_id'    => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'name'          => 'required|string|max:255',
            'designation'   => 'nullable|string|max:255',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string|max:20',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }
}
