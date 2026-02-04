<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuper = $user->role === 'superadmin';

        $query = Department::with(['company', 'branch'])->latest();

        if (!$isSuper) {
            $query->where('company_id', $user->company_id);
        }

        if ($isSuper && request()->filled('company_id')) {
            $query->where('company_id', request('company_id'));
        }

        if (request()->filled('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('branch', function ($b) use ($search) {
                      $b->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('company', function ($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $departments = $query->paginate(10)->appends(request()->query());

        $companies = $isSuper
            ? Company::orderBy('name')->get()
            : Company::where('id', $user->company_id)->get();

        $branches = collect();
        if (request()->filled('company_id')) {
            $branches = Branch::where('company_id', request('company_id'))->orderBy('name')->get();
        } elseif (!$isSuper) {
            $user = auth()->user();
            // Get user's assigned branch IDs from the pivot table
            $userBranchIds = $user->branches()->pluck('branches.id')->toArray();
            
            if (!empty($userBranchIds)) {
                $branches = Branch::whereIn('id', $userBranchIds)->orderBy('name')->get();
            } else {
                // Fallback to single branch if user has branch_id set
                if ($user->branch_id) {
                    $branches = Branch::where('id', $user->branch_id)->orderBy('name')->get();
                } else {
                    // If no branches assigned, filter by company
                    $branches = Branch::where('company_id', $user->company_id)->orderBy('name')->get();
                }
            }
        }

        return view('departments.index', [
            'departments' => $departments,
            'companies' => $companies,
            'branches' => $branches,
            'isSuper' => $isSuper,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->role === 'superadmin') {
            $companies = Company::orderBy('name')->get();
            $branches = collect();
        } else {
            $companies = Company::where('id', $user->company_id)->get();
            // Only show branches assigned to this user
            $branches = $user->branches()->orderBy('name')->get();
        }

        return view('departments.create', compact('companies', 'branches'));
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
            'branch_id' => 'required|exists:branches,id',
        ]);

        $branch = Branch::where('id', $validated['branch_id'])
            ->where('company_id', $validated['company_id'])
            ->first();

        if (!$branch) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['branch_id' => 'Selected branch does not belong to the chosen company.']);
        }

        // Check for duplicate department name within the same branch
        $exists = Department::where('branch_id', $validated['branch_id'])
            ->whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => 'A department with this name already exists in this branch.']);
        }

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department added.');
    }

    public function edit(Department $department)
    {
        $user = auth()->user();
        
        if ($user->role !== 'superadmin' && $department->company_id !== $user->company_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === 'superadmin') {
            $companies = Company::all();
            $branches = Branch::where('company_id', $department->company_id)->get();
        } else {
            $companies = Company::where('id', $user->company_id)->get();
            // Only show branches assigned to this user
            $branches = $user->branches()->orderBy('name')->get();
        }

        return view('departments.edit', compact('department', 'companies', 'branches'));
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
            'branch_id' => 'required|exists:branches,id',
        ]);

        $branch = Branch::where('id', $validated['branch_id'])
            ->where('company_id', $validated['company_id'])
            ->first();

        if (!$branch) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['branch_id' => 'Selected branch does not belong to the chosen company.']);
        }

        // Check for duplicate department name within the same branch (excluding current department)
        $exists = Department::where('branch_id', $validated['branch_id'])
            ->where('id', '!=', $department->id)
            ->whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => 'A department with this name already exists in this branch.']);
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

    public function getByCompany(Company $company)
    {
        $query = $company->departments()->select('id', 'name', 'company_id');

        // Filter by user's assigned departments
        $user = auth()->user();
        if ($user && $user->role !== 'superadmin' && $user->departments()->exists()) {
             $query->whereIn('id', $user->departments()->pluck('departments.id'));
        }

        // Filter by specific branch(es) if provided
        if (request()->filled('branch_id')) {
            $branchIds = is_array(request('branch_id')) ? request('branch_id') : [request('branch_id')];
            $query->whereIn('branch_id', $branchIds);
        }

        $departments = $query->orderBy('name')->get();
        
        return response()->json($departments)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
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
