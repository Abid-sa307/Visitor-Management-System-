<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        // Super admin sees all, company users see only their company's departments
        if (auth()->user()->role === 'superadmin') {
            $departments = Department::with('company')->latest()->paginate(10);
        } else {
            $departments = Department::with('company')
                ->where('company_id', auth()->user()->company_id)
                ->latest()
                ->paginate(10);
        }

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        // Super admin can choose any company, others only their own
        if (auth()->user()->role === 'superadmin') {
            $companies = Company::all();
        } else {
            $companies = Company::where('id', auth()->user()->company_id)->get();
        }

        return view('departments.create', compact('companies'));
    }

    public function store(Request $request)
    {
        // Force company_id for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $request->merge(['company_id' => auth()->user()->company_id]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department added.');
    }

    public function edit(Department $department)
    {
        // Restrict edit if not same company
        if (auth()->user()->role !== 'superadmin' && $department->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->role === 'superadmin') {
            $companies = Company::all();
        } else {
            $companies = Company::where('id', auth()->user()->company_id)->get();
        }

        return view('departments.edit', compact('department', 'companies'));
    }

    public function update(Request $request, Department $department)
    {
        if (auth()->user()->role !== 'superadmin' && $department->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->role !== 'superadmin') {
            $request->merge(['company_id' => auth()->user()->company_id]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        if (auth()->user()->role !== 'superadmin' && $department->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted.');
    }

    /**
     * JSON endpoint to fetch departments for a company (AJAX).
     * Secured so company users can only fetch their own company's departments.
     */
    public function getByCompany($companyId)
    {
        if (auth()->user()->role !== 'superadmin') {
            // Ignore passed $companyId and use user's own company
            $companyId = auth()->user()->company_id;
        }

        return Department::where('company_id', $companyId)->get();
    }
}
