<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
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
        return view('visitors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            // Add other validations
        ]);

        Visitor::create($validated);

        return redirect()->route('visitors.index')->with('success', 'Visitor added successfully.');
    }

    public function edit(Visitor $visitor)
    {
        return view('visitors.edit', compact('visitor'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            // More fields...
        ]);

        $visitor->update($validated);

        return redirect()->route('visitors.index')->with('success', 'Visitor updated.');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('visitors.index')->with('success', 'Visitor deleted.');
    }
}
