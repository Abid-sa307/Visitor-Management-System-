<?php

namespace App\Http\Controllers;

use App\Models\VisitorCategory;
use App\Http\Requests\VisitorCategoryRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = VisitorCategory::query()
            ->when($request->has('company_id'), function($query) use ($request) {
                return $query->where('company_id', $request->company_id);
            })
            ->with('company')
            ->latest()
            ->paginate(10);

        $companies = auth()->user()->hasRole('superadmin') 
            ? Company::orderBy('name')->pluck('name', 'id')
            : collect();

        return view('visitor-categories.index', compact('categories', 'companies'));
    }

    public function create()
    {
        $companies = collect();
        
        if (auth()->user()->hasRole('superadmin')) {
            // Remove the active() scope to show all companies
            $companies = Company::orderBy('name')->pluck('name', 'id');
        }

        return view('visitor-categories.create', compact('companies'));
    }

    public function store(VisitorCategoryRequest $request)
    {
        $data = $request->validated();
        
        // For company users, set their company_id
        if (auth()->guard('company')->check()) {
            $data['company_id'] = auth()->guard('company')->id();
        }

        VisitorCategory::create($data);

        return redirect()->route('visitor-categories.index')
            ->with('success', 'Visitor category created successfully.');
    }

    public function show(VisitorCategory $visitorCategory)
    {
        $this->authorize('view', $visitorCategory);
        return view('visitor-categories.show', compact('visitorCategory'));
    }

    public function edit(VisitorCategory $visitorCategory)
    {
        $this->authorize('update', $visitorCategory);
        
        $companies = auth()->user()->hasRole('superadmin') 
            ? Company::orderBy('name')->pluck('name', 'id')
            : null;

        return view('visitor-categories.edit', compact('visitorCategory', 'companies'));
    }

    public function update(VisitorCategoryRequest $request, VisitorCategory $visitorCategory)
    {
        $this->authorize('update', $visitorCategory);
        
        $visitorCategory->update($request->validated());

        return redirect()->route('visitor-categories.index')
            ->with('success', 'Visitor category updated successfully');
    }

    public function destroy(VisitorCategory $visitorCategory)
    {
        $this->authorize('delete', $visitorCategory);
        
        if ($visitorCategory->visitors()->exists()) {
            return back()->with('error', 'Cannot delete category with associated visitors.');
        }

        $visitorCategory->delete();

        return redirect()->route('visitor-categories.index')
            ->with('success', 'Visitor category deleted successfully');
    }
}