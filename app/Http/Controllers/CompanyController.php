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
use App\Models\Department;

class CompanyController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $companies = Company::with('branches')->latest()->paginate(10);
        return view('companies.index', compact('companies'));
    }

    public function branches(Company $company)
    {
        $branches = $company->branches()->get();
        return view('companies.branches', compact('company', 'branches'));
    }

   // In CompanyController.php

// In CompanyController.php

/**
 * Get branches for a company as JSON
 *
 * @param Company $company
 * @return \Illuminate\Http\JsonResponse
 */
public function getBranches(Company $company)
{
    try {
        $branches = $company->branches()
            ->select('id', 'name') // or 'id','name','address' if that column exists
            ->orderBy('name')
            ->get()
            ->map(fn($branch) => [
                'id'   => $branch->id,
                'name' => $branch->name,
            ]);

        return response()->json($branches);
    } catch (\Exception $e) {
        \Log::error('Error fetching branches: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to load branches'], 500);
    }
}


/**
 * Get departments for a company as JSON
 *
 * @param Company $company
 * @return \Illuminate\Http\JsonResponse
 */
public function getDepartments(Company $company)
{
    try {
        $departments = $company->departments()
            ->select('id', 'name')   // 👈 only use columns you are sure exist
            ->orderBy('name')
            ->get()
            ->map(function ($dept) {
                return [
                    'id'   => $dept->id,
                    'name' => $dept->name,
                ];
            });

        return response()->json($departments);
    } catch (\Throwable $e) {
        \Log::error('Error fetching departments', [
            'company_id' => $company->id,
            'error'      => $e->getMessage(),
        ]);

        // Return an error so your JS shows "Error loading departments"
        return response()->json(['error' => 'Failed to load departments'], 500);
    }
}


    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        try {
            // Validation rules
            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'nullable|email|unique:companies,email',
                'phone'  => 'nullable|string|max:32',
                'address' => 'nullable|string',
                'contact_number' => 'nullable|string|max:255',
                'branch_start_date' => 'nullable|date',
                'branch_end_date' => 'nullable|date|after_or_equal:branch_start_date',
            ]);

            // Create new company
            $company = new Company($validated);
            $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');
            $company->address = $validated['address'] ?? '';
            $company->contact_number = $validated['contact_number'] ?? '';
            $company->save();

            // Handle branches if provided
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
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) { // Duplicate entry error code
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'A company with this email already exists. Please use a different email address.');
            }
            
            // For other database errors
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while saving the company. Please try again.');
        } catch (\Exception $e) {
            // For any other exceptions
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Return branches JSON for the given company (used by user form AJAX)
     */
    // public function getBranches(Company $company)
    // {
    //     return response()->json($company->branches()->select('id','name')->orderBy('name')->get());
    // }

    public function update(Request $request, Company $company)
    {
        try {
            // Validation rules
            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'nullable|email|unique:companies,email,'.$company->id,
                'phone'  => 'nullable|string|max:32',
                'address' => 'nullable|string',
                'contact_number' => 'nullable|string|max:255',
                'branch_start_date' => 'nullable|date',
                'branch_end_date' => 'nullable|date|after_or_equal:branch_start_date',
                'auto_approve_visitors' => 'sometimes|boolean',
                'face_recognition_enabled' => 'sometimes|boolean',
            ]);

            // Update company attributes
            $company->fill($validated);
            $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');
            $company->face_recognition_enabled = $request->boolean('face_recognition_enabled');
            $company->save();

            // Handle branches if provided
            $branches = $request->input('branches', []);
            
            // Debug log the input
            \Log::info('Branches input:', ['branches' => $branches]);
            
            if (!empty($branches) && is_array($branches)) {
                $ids = array_values($branches['id'] ?? []);
                $names = array_values($branches['name'] ?? []);
                $phones = array_values($branches['phone'] ?? []);
                $emails = array_values($branches['email'] ?? []);
                $addresses = array_values($branches['address'] ?? []);
                $startTimes = array_values($branches['start_time'] ?? []);
                $endTimes = array_values($branches['end_time'] ?? []);

                // Update existing and create new branches
                $count = max(
                    count($names), 
                    count($phones), 
                    count($emails), 
                    count($addresses), 
                    count($startTimes),
                    count($endTimes),
                    count($ids)
                );
                
                $keptBranchIds = [];
                
                for ($i = 0; $i < $count; $i++) {
                    $name = trim((string)($names[$i] ?? ''));
                    $id = (int)($ids[$i] ?? 0);
                    $data = [
                        'name' => $name,
                        'phone' => (string)($phones[$i] ?? ''),
                        'email' => (string)($emails[$i] ?? ''),
                        'address' => (string)($addresses[$i] ?? ''),
                        'start_time' => !empty($startTimes[$i]) ? $startTimes[$i] : null,
                        'end_time' => !empty($endTimes[$i]) ? $endTimes[$i] : null,
                    ];

                    if ($id > 0) {
                        $branch = Branch::where('company_id', $company->id)
                            ->where('id', $id)
                            ->first();
                        
                        if ($branch) {
                            if ($name === '') {
                                $branch->delete();
                                continue;
                            } else {
                                $branch->update($data);
                                $keptBranchIds[] = $branch->id;
                            }
                        }
                    } else {
                        // Create new branch if any field is provided
                        $hasAny = ($name !== '') || ($data['phone'] !== '') || 
                                 ($data['email'] !== '') || ($data['address'] !== '');
                        
                        if ($hasAny) {
                            if ($name === '') { 
                                $data['name'] = 'Branch ' . ($company->branches()->count() + 1); 
                            }
                            $newBranch = Branch::create($data + ['company_id' => $company->id]);
                            $keptBranchIds[] = $newBranch->id;
                        }
                    }
                }

                // Delete branches not present in submission (only when at least one existing ID is submitted)
                if (!empty($keptBranchIds)) {
                    Branch::where('company_id', $company->id)
                        ->whereNotIn('id', $keptBranchIds)
                        ->delete();
                }
            }

            return redirect()
                ->route('companies.index')
                ->with('success', 'Company updated successfully.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'A company with this email already exists. Please use a different email address.');
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the company. Please try again.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
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
    
    // Eager load branches with search if needed
    $search = request('search');
    $branches = $company->branches()
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        })
        ->get();

    // Replace the branches collection on the company
    $company->setRelation('branches', $branches);

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
