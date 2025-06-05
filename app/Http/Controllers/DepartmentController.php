<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('company')->latest()->paginate(10);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('departments.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department added.');
    }

    public function edit(Department $department)
    {
        $companies = Company::all();
        return view('departments.edit', compact('department', 'companies'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted.');
    }
}
