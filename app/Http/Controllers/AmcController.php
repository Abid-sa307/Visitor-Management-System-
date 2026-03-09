<?php

namespace App\Http\Controllers;

use App\Models\AmcRecord;
use App\Models\Company;
use App\Models\Branch;
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
        $companyId = $request->input('company_id');
        $branchIds = $request->input('branch_ids', []);

        $query = Branch::query()->with('company');

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if (!empty($branchIds)) {
            $query->whereIn('id', (array)$branchIds);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('company', fn($cq) => $cq->where('name', 'like', '%' . $search . '%'));
            });
        }

        $branches = $query->orderBy('name')
            ->with(['amcRecords' => fn($q) => $q->latest('start_date')])
            ->paginate(20)
            ->withQueryString();

        $companies = Company::orderBy('name')->get();
        $branchOptions = $companyId ?Branch::where('company_id', $companyId)->orderBy('name')->get() : collect();

        return view('amc.index', [
            'branches' => $branches,
            'companies' => $companies,
            'branchOptions' => $branchOptions,
            'search' => $search,
            'company_id' => $companyId,
            'branch_id' => null, // Legacy support if needed
            'branch_ids' => (array)$branchIds
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'superadmin')
            abort(403);

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'package_name' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'payment_date' => 'nullable|date',
            'payment_mode' => 'nullable|string|max:100',
            'transaction_reference' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,expired,upcoming',
            'notes' => 'nullable|string|max:2000',
        ]);

        AmcRecord::create($validated);

        return back()->with('success', 'AMC record added successfully.');
    }

    public function update(Request $request, AmcRecord $amcRecord)
    {
        if (auth()->user()->role !== 'superadmin')
            abort(403);

        $validated = $request->validate([
            'package_name' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'payment_date' => 'nullable|date',
            'payment_mode' => 'nullable|string|max:100',
            'transaction_reference' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,expired,upcoming',
            'notes' => 'nullable|string|max:2000',
        ]);

        $amcRecord->update($validated);

        return back()->with('success', 'AMC record updated successfully.');
    }

    public function destroy(AmcRecord $amcRecord)
    {
        if (auth()->user()->role !== 'superadmin')
            abort(403);
        $amcRecord->delete();
        return back()->with('success', 'AMC record deleted.');
    }
}
