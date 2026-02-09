<?php

namespace App\Http\Controllers;

use App\Models\VisitorCategory;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;

class VisitorCategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuper = $user->role === 'superadmin' || $user->role === 'super_admin';
        
        $query = VisitorCategory::with('company');

        if (!$isSuper) {
            $query->where('company_id', $user->company_id);
        }

        // Filter by company (Super Admin only)
        if ($isSuper && $request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter by branch (Multi-select)
        $branchIds = $request->input('branch_ids');
        if (!$branchIds && $request->filled('branch_id')) {
            $branchIds = [$request->input('branch_id')];
        }
        
        if (!empty($branchIds)) {
            $query->whereIn('branch_id', (array)$branchIds);
        }

        $categories = $query->latest()->paginate(10);

        // Data for filters
        $companies = [];
        $branches = [];

        if ($isSuper) {
            $companies = Company::orderBy('name')->pluck('name', 'id')->toArray();
            if ($request->filled('company_id')) {
                $branches = Branch::where('company_id', $request->company_id)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->toArray();
            } else {
                $branches = Branch::orderBy('name')->pluck('name', 'id')->toArray();
            }
        } else {
            // For company users, get their company's branches
            // Respect user's branch assignment if any?
            // Usually keeping it consistently "Company Branches" for filtering is safer for now, 
            // similar to User controller logic.
            $branches = Branch::where('company_id', $user->company_id)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        return view('visitor-categories.index', compact('categories', 'companies', 'branches', 'isSuper'));
    }

    public function create()
    {
        $user = auth()->user();
        $companies = Company::pluck('name', 'id');
        $branches = [];
        
        if ($user->role === 'superadmin') {
            $branches = Branch::pluck('name', 'id');
        } else {
            $companies = [$user->company_id => $user->company->name];
            
            // Get user's assigned branch IDs from the pivot table
            $userBranchIds = $user->branches()->pluck('branches.id')->toArray();
            
            if (!empty($userBranchIds)) {
                // Filter branches by user's assigned branches
                $branches = Branch::whereIn('id', $userBranchIds)->pluck('name', 'id');
            } else {
                // Fallback to single branch if user has branch_id set
                if ($user->branch_id) {
                    $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
                } else {
                    // If no branches assigned, get all company branches
                    $branches = Branch::where('company_id', $user->company_id)->pluck('name', 'id');
                }
            }
            
            // If company has no branches, add a "None" option
            if ($branches->isEmpty()) {
                $branches = collect(['none' => 'None']);
            }
        }

        return view('visitor-categories.create', [
            'companies' => $companies,
            'branches' => $branches,
            'isSuperAdmin' => $user->role === 'superadmin'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_id' => auth()->user()->hasRole('superadmin') ? 'required|exists:companies,id' : '',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean'
        ]);

        // Validate unique name within the same branch
        $companyId = auth()->user()->role !== 'superadmin' ? auth()->user()->company_id : $data['company_id'];
        $branchId = $data['branch_id'] ?? null;
        
        $exists = VisitorCategory::where('company_id', $companyId)
            ->where('name', $data['name'])
            ->where(function($q) use ($branchId) {
                if ($branchId) {
                    $q->where('branch_id', $branchId);
                } else {
                    $q->whereNull('branch_id');
                }
            })
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['name' => 'A category with this name already exists in this branch.'])->withInput();
        }

        if (auth()->user()->role !== 'superadmin') {
            $data['company_id'] = auth()->user()->company_id;
            // Ensure the branch belongs to the user's company
            if (isset($data['branch_id'])) {
                $branch = Branch::where('id', $data['branch_id'])
                    ->where('company_id', $data['company_id'])
                    ->firstOrFail();
            }
        } else if (isset($data['branch_id'])) {
            // For superadmin, ensure the branch belongs to the selected company
            $branch = Branch::where('id', $data['branch_id'])
                ->where('company_id', $data['company_id'])
                ->firstOrFail();
        }

        $data['is_active'] = $request->has('is_active');
        
        VisitorCategory::create($data);

        return redirect()->route('visitor-categories.index')
            ->with('success', 'Visitor category created successfully.');
    }

    public function edit(VisitorCategory $visitorCategory)
    {
        $companies = [];
        $branches = [];
        
        if (auth()->user()->role === 'superadmin') {
            $companies = Company::pluck('name', 'id');
            $branches = Branch::where('company_id', $visitorCategory->company_id)->pluck('name', 'id');
        } else {
            // Non-superadmin can only edit their own company's categories
            if ($visitorCategory->company_id !== auth()->user()->company_id) {
                abort(403);
            }
            $companies = [$visitorCategory->company_id => $visitorCategory->company->name];
            
            // Get user's assigned branch IDs from the pivot table
            $user = auth()->user();
            $userBranchIds = $user->branches()->pluck('branches.id')->toArray();
            
            if (!empty($userBranchIds)) {
                // Filter branches by user's assigned branches
                $branches = Branch::whereIn('id', $userBranchIds)->pluck('name', 'id');
            } else {
                // Fallback to single branch if user has branch_id set
                if ($user->branch_id) {
                    $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
                } else {
                    // If no branches assigned, get all company branches
                    $branches = Branch::where('company_id', $visitorCategory->company_id)->pluck('name', 'id');
                }
            }
            
            // If company has no branches, add a "None" option
            if ($branches->isEmpty()) {
                $branches = collect(['none' => 'None']);
            }
        }

        return view('visitor-categories.edit', compact('visitorCategory', 'companies', 'branches'));
    }

    public function update(Request $request, VisitorCategory $visitorCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_id' => auth()->user()->role === 'superadmin' ? 'required|exists:companies,id' : '',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean'
        ]);

        // Validate unique name within the same branch (excluding current category)
        $companyId = auth()->user()->role !== 'superadmin' ? auth()->user()->company_id : $data['company_id'];
        $branchId = $data['branch_id'] ?? null;
        
        $exists = VisitorCategory::where('company_id', $companyId)
            ->where('name', $data['name'])
            ->where('id', '!=', $visitorCategory->id)
            ->where(function($q) use ($branchId) {
                if ($branchId) {
                    $q->where('branch_id', $branchId);
                } else {
                    $q->whereNull('branch_id');
                }
            })
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['name' => 'A category with this name already exists in this branch.'])->withInput();
        }

        if (auth()->user()->role !== 'superadmin') {
            // Non-superadmin can only update their own company's categories
            if ($visitorCategory->company_id !== auth()->user()->company_id) {
                abort(403);
            }
            $data['company_id'] = auth()->user()->company_id;
            
            // Ensure the branch belongs to the user's company
            if (isset($data['branch_id'])) {
                $branch = Branch::where('id', $data['branch_id'])
                    ->where('company_id', $data['company_id'])
                    ->firstOrFail();
            }
        } else if (isset($data['branch_id'])) {
            // For superadmin, ensure the branch belongs to the selected company
            $branch = Branch::where('id', $data['branch_id'])
                ->where('company_id', $data['company_id'])
                ->firstOrFail();
        }

        $data['is_active'] = $request->has('is_active');
        
        $visitorCategory->update($data);

        return redirect()->route('visitor-categories.index')
            ->with('success', 'Visitor category updated successfully.');
    }

    public function destroy(VisitorCategory $visitorCategory)
    {
        if ($visitorCategory->visitors()->exists()) {
            return back()->with('error', 'Cannot delete category with associated visitors.');
        }

        $visitorCategory->delete();

        return redirect()->route('visitor-categories.index')
            ->with('success', 'Visitor category deleted successfully.');
    }
}