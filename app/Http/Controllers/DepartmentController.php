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
        
        // Check for duplicate department name within the same company
        $exists = Department::where('company_id', $validated['company_id'])
            ->whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])
            ->exists();
        
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => 'A department with this name already exists in this company.']);
        }

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
        
        // Check for duplicate department name within the same company (excluding current department)
        $exists = Department::where('company_id', $validated['company_id'])
            ->where('id', '!=', $department->id)
            ->whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])
            ->exists();
        
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => 'A department with this name already exists in this company.']);
        }

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully');
    }

    /**
     * Get departments by company (API)
     */
    public function getByCompany(Company $company)
    {
        try {
            $departments = $company->departments()->select('id', 'name', 'company_id')->get();
            
            return response()->json($departments)
                ->header('Content-Type', 'application/json')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            \Log::error('Error fetching departments: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load departments',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * JSON endpoint to fetch departments for a company (AJAX).
     * Secured so company users can only fetch their own company's departments.
     */
    public function getByCompanyAjax($companyId)
    {
        $user = auth()->user();
        
        // If not superadmin, ensure they can only access their own company's departments
        if (!in_array($user->role, ['superadmin', 'super_admin'])) {
            $companyId = $user->company_id;
        }

        $departments = Department::where('company_id', $companyId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($departments);
    }
}
