<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('branches')->latest()->paginate(10);
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
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:255',
            
            // add the rest of your fields here...
            // do NOT add auto_approve_visitors to $validated; we set it explicitly below
        ]);

        $company = new Company($validated);

        // ✅ Robustly persist the checkbox (true/false)
        $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');

        // Ensure NOT NULL DB columns are always set
        $company->address = $validated['address'] ?? '';
        $company->contact_number = $validated['contact_number'] ?? '';

        // If you handle file uploads (logos etc.), keep your existing code here
        // if ($request->hasFile('logo')) { ... }

        $company->save();
        // Create branches provided (new only)
        $branches = $request->input('branches', []);
        if (!empty($branches) && is_array($branches)) {
            $names = array_values($branches['name'] ?? []);
            $phones = array_values($branches['phone'] ?? []);
            $emails = array_values($branches['email'] ?? []);
            $addresses = array_values($branches['address'] ?? []);
            $count = max(count($names), count($phones), count($emails), count($addresses));
            for ($i = 0; $i < $count; $i++) {
                $nm = trim((string)($names[$i] ?? ''));
                $ph = (string)($phones[$i] ?? '');
                $em = (string)($emails[$i] ?? '');
                $ad = (string)($addresses[$i] ?? '');
                if ($nm === '' && $ph === '' && $em === '' && $ad === '') continue;
                Branch::create([
                    'company_id' => $company->id,
                    'name'   => $nm !== '' ? $nm : 'Branch',
                    'phone'  => $ph,
                    'email'  => $em,
                    'address'=> $ad,
                ]);
            }
        }

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Return branches JSON for the given company (used by user form AJAX)
     */
    public function getBranches(Company $company)
    {
        return response()->json($company->branches()->select('id','name')->orderBy('name')->get());
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email',
            'phone'  => 'nullable|string|max:32',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:255',
            // add the rest of your fields here...
        ]);

        // Update normal attributes
        $company->fill($validated);

        // ✅ Robustly persist the checkbox (true/false)
        $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');

        // Ensure NOT NULL DB columns are always set
        $company->address = $validated['address'] ?? ($company->address ?? '');
        $company->contact_number = $validated['contact_number'] ?? ($company->contact_number ?? '');

        // If you handle file uploads (logos etc.), keep your existing code here
        // if ($request->hasFile('logo')) { ... }

        $company->save();

        // Persist branches (create/update/delete)
        $branches = $request->input('branches', []);
        $ids      = array_values($branches['id']     ?? []);
        $names    = array_values($branches['name']   ?? []);
        $phones   = array_values($branches['phone']  ?? []);
        $emails   = array_values($branches['email']  ?? []);
        $addresses= array_values($branches['address']?? []);

        // Update existing and create new
        $count = max(count($names), count($phones), count($emails), count($addresses), count($ids));
        for ($i = 0; $i < $count; $i++) {
            $name = trim((string)($names[$i] ?? ''));
            $id   = (int)($ids[$i] ?? 0);
            $data = [
                'name'    => $name,
                'phone'   => (string)($phones[$i] ?? ''),
                'email'   => (string)($emails[$i] ?? ''),
                'address' => (string)($addresses[$i] ?? ''),
            ];

            if ($id > 0) {
                $branch = Branch::where('company_id', $company->id)->where('id', $id)->first();
                if ($branch) {
                    if ($name === '') {
                        $branch->delete();
                    } else {
                        $branch->update($data);
                    }
                }
            } else {
                // Create if any field provided
                $hasAny = ($name !== '') || ($data['phone'] !== '') || ($data['email'] !== '') || ($data['address'] !== '');
                if ($hasAny) {
                    if ($name === '') { $data['name'] = 'Branch'; }
                    Branch::create($data + ['company_id' => $company->id]);
                }
            }
        }

        // Delete branches not present in submission (ONLY when at least one existing ID submitted)
        $keep = array_values(array_filter(array_map('intval', $ids))); // existing IDs only
        if (!empty($keep)) {
            Branch::where('company_id', $company->id)
                ->whereNotIn('id', $keep)
                ->delete();
        }

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
