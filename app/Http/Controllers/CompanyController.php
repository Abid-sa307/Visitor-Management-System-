<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Branch;
use App\Models\User;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanyController extends Controller
{
    use AuthorizesRequests;
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

    /**
     * Display the QR code management page for a company.
     */
    /**
     * Display a listing of companies for QR code management.
     */
    public function qrIndex()
    {
        $this->authorize('viewAny', Company::class);
        
        $companies = Company::withCount('branches')
            ->orderBy('name')
            ->get();
            
        return view('companies.qr-index', compact('companies'));
    }
    
    /**
     * Display the QR code management page for a specific company.
     */
    public function qr(Company $company)
    {
        $this->authorize('view', $company);
        
        // Eager load the branches relationship with proper ordering
        $company->load(['branches' => function($query) {
            $query->orderBy('name');
        }]);
        
        // Debug: Log the company and branches data
        \Log::info('Company QR View', [
            'company_id' => $company->id,
            'branches_count' => $company->branches ? $company->branches->count() : 0,
            'branches' => $company->branches ? $company->branches->toArray() : []
        ]);
        
        return view('companies.qr', compact('company'));
    }

    /**
     * Download the QR code for a company.
     */
    public function downloadQr(Company $company, Branch $branch = null)
    {
        $this->authorize('view', $company);
        
        // Generate QR code data
        $qrData = [
            'company' => $company->name,
            'email' => $company->email,
            'contact' => $company->contact_number,
            'branch' => $branch ? $branch->name : null,
            'branch_address' => $branch ? $branch->address : null,
            'checkin_url' => $branch 
                ? route('qr.scan', ['company' => $company, 'branch' => $branch])
                : route('qr.scan', $company)
        ];
        
        // Format as human-readable text
        $qrText = "Company: " . $qrData['company'] . "\n";
        $qrText .= "Email: " . $qrData['email'] . "\n";
        $qrText .= "Contact: " . $qrData['contact'] . "\n";
        
        if ($branch) {
            $qrText .= "\nBranch: " . $qrData['branch'] . "\n";
            if (!empty($branch->address)) {
                $qrText .= "Address: " . $qrData['branch_address'] . "\n";
            }
        }
        
        $qrText .= "\nCheck-in URL: " . $qrData['checkin_url'];
        
        // Generate QR code as SVG string
        $qrCode = QrCode::format('svg')
            ->size(1000) // Larger size for better quality when printed
            ->generate($qrText);
        
        // Create a descriptive filename
        $filename = 'qr-code-' . 
                   str_slug($company->name) . 
                   ($branch ? '-' . str_slug($branch->name) : '') . 
                   '.svg';
        
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
