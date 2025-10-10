<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(10);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        // Keep your existing validations; these are typical examples:
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email',
            'phone'  => 'nullable|string|max:32',
            // add the rest of your fields here...
            // do NOT add auto_approve_visitors to $validated; we set it explicitly below
        ]);

        $company = new Company($validated);

        // ✅ Robustly persist the checkbox (true/false)
        $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');

        // If you handle file uploads (logos etc.), keep your existing code here
        // if ($request->hasFile('logo')) { ... }

        $company->save();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email',
            'phone'  => 'nullable|string|max:32',
            // add the rest of your fields here...
        ]);

        // Update normal attributes
        $company->fill($validated);

        // ✅ Robustly persist the checkbox (true/false)
        $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');

        // If you handle file uploads (logos etc.), keep your existing code here
        // if ($request->hasFile('logo')) { ... }

        $company->save();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
