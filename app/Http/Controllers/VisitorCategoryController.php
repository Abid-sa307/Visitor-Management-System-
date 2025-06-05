<?php

namespace App\Http\Controllers;

use App\Models\VisitorCategory;
use App\Models\Company;
use Illuminate\Http\Request;

class VisitorCategoryController extends Controller
{
    public function index()
    {
        $categories = VisitorCategory::with('company')->latest()->paginate(10);
        return view('visitor_categories.index', compact('categories'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('visitor_categories.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
        ]);

        VisitorCategory::create($validated);

        return redirect()->route('visitor-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(VisitorCategory $visitor_category)
    {
        $companies = Company::all();
        return view('visitor_categories.edit', compact('visitor_category', 'companies'));
    }

    public function update(Request $request, VisitorCategory $visitor_category)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
        ]);

        $visitor_category->update($validated);

        return redirect()->route('visitor-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(VisitorCategory $visitor_category)
    {
        $visitor_category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
