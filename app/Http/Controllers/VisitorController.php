<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Company;
use App\Models\Department;
use App\Models\VisitorCategory;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        $visitors = Visitor::latest()->paginate(10);
        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        $companies = Company::all();
        $departments = Department::all();
        $categories = VisitorCategory::all();
        return view('visitors.create', compact('companies', 'departments', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'visitor_category_id' => 'nullable|exists:visitor_categories,id',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'department_id' => 'nullable|exists:departments,id',
            'purpose' => 'nullable|string|max:255',
            'person_to_visit' => 'nullable|string|max:255',
            'in_time' => 'nullable|date',
            'out_time' => 'nullable|date',
            'status' => 'required|in:Pending,Approved,Rejected',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('documents')) {
            $paths = [];
            foreach ($request->file('documents') as $doc) {
                $paths[] = $doc->store('documents', 'public');
            }
            $validated['documents'] = $paths;
        }

        Visitor::create($validated);

        return redirect()->route('visitors.index')->with('success', 'Visitor added successfully.');
    }


    public function edit(Visitor $visitor)
    {
        $companies = Company::all();
        $departments = Department::all();
        $categories = VisitorCategory::all();
        return view('visitors.edit', compact('visitor', 'companies', 'departments', 'categories'));
    }

    public function update(Request $request, Visitor $visitor)
{
    $validated = $request->validate([
        'company_id' => 'required|exists:companies,id',
        'name' => 'required|string|max:255',
        'visitor_category_id' => 'nullable|exists:visitor_categories,id',
        'email' => 'nullable|email',
        'phone' => 'required|string|max:15',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'department_id' => 'nullable|exists:departments,id',
        'purpose' => 'nullable|string|max:255',
        'person_to_visit' => 'nullable|string|max:255',
        'in_time' => 'nullable|date',
        'out_time' => 'nullable|date',
        'status' => 'required|in:Pending,Approved,Rejected',
        'documents' => 'nullable|array',
        'documents.*' => 'file|max:5120',
    ]);

    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('photos', 'public');
    }

    if ($request->hasFile('documents')) {
        $paths = [];
        foreach ($request->file('documents') as $doc) {
            $paths[] = $doc->store('documents', 'public');
        }
        $validated['documents'] = $paths;
    }

    $visitor->update($validated);

    return redirect()->route('visitors.index')->with('success', 'Visitor updated successfully.');
}



    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('visitors.index')->with('success', 'Visitor deleted successfully.');
    }
}
