<?php

namespace App\Http\Controllers;

use App\Models\VisitorCategory;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;

class VisitorCategoryController extends Controller
{
    public function index()
    {
        $categories = VisitorCategory::with('company')
            ->when(auth()->user()->role !== 'superadmin', function($q) {
                return $q->where('company_id', auth()->user()->company_id);
            })
            ->latest()
            ->paginate(10);

        return view('visitor-categories.index', compact('categories'));
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
            $branches = Branch::where('company_id', $user->company_id)->pluck('name', 'id');
            
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
            $branches = Branch::where('company_id', $visitorCategory->company_id)->pluck('name', 'id');
            
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