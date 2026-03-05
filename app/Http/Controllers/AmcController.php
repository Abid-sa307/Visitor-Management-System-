<?php

namespace App\Http\Controllers;

use App\Models\AmcRecord;
use App\Models\Company;
use Illuminate\Http\Request;

class AmcController extends Controller
{
    public function index(Request $request)
    {
        // Only superadmin can access
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $search = $request->input('search');

        $companies = Company::query()
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->orderBy('name')
            ->with(['amcRecords' => fn($q) => $q->latest('start_date')])
            ->paginate(20)
            ->withQueryString();

        return view('amc.index', compact('companies', 'search'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);

        $validated = $request->validate([
            'company_id'            => 'required|exists:companies,id',
            'package_name'          => 'nullable|string|max:255',
            'amount'                => 'nullable|numeric|min:0',
            'start_date'            => 'nullable|date',
            'end_date'              => 'nullable|date|after_or_equal:start_date',
            'payment_date'          => 'nullable|date',
            'payment_mode'          => 'nullable|string|max:100',
            'transaction_reference' => 'nullable|string|max:255',
            'status'                => 'nullable|in:active,expired,upcoming',
            'notes'                 => 'nullable|string|max:2000',
        ]);

        AmcRecord::create($validated);

        return back()->with('success', 'AMC record added successfully.');
    }

    public function update(Request $request, AmcRecord $amcRecord)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);

        $validated = $request->validate([
            'package_name'          => 'nullable|string|max:255',
            'amount'                => 'nullable|numeric|min:0',
            'start_date'            => 'nullable|date',
            'end_date'              => 'nullable|date|after_or_equal:start_date',
            'payment_date'          => 'nullable|date',
            'payment_mode'          => 'nullable|string|max:100',
            'transaction_reference' => 'nullable|string|max:255',
            'status'                => 'nullable|in:active,expired,upcoming',
            'notes'                 => 'nullable|string|max:2000',
        ]);

        $amcRecord->update($validated);

        return back()->with('success', 'AMC record updated successfully.');
    }

    public function destroy(AmcRecord $amcRecord)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);
        $amcRecord->delete();
        return back()->with('success', 'AMC record deleted.');
    }
}
