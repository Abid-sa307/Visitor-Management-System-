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
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    /**
     * Generate QR code for a company
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function generateQrCode(Company $company)
    {
        $this->authorize('view', $company);
        
        // Generate QR code URL
        $url = route('qr.scan', ['company' => $company->id]);
        
        // Generate QR code
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($url);
        
        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'inline; filename="' . Str::slug($company->name) . '-qrcode.svg"'
        ]);
    }
    use AuthorizesRequests;

    /**
     * Show the QR code page
     *
     * @param Company $company
     * @return \Illuminate\View\View
     */
    /**
     * Show the public QR code page (no authentication required)
     *
     * @param Company $company
     * @return \Illuminate\View\View
     */
    public function showPublicQrPage($companyId)
    {
        // Manually find the company or return 404
        $company = Company::findOrFail($companyId);
        
        // Get branch if branch_id is provided
        $branch = null;
        if (request()->has('branch_id')) {
            $branch = Branch::where('company_id', $company->id)
                          ->findOrFail(request('branch_id'));
        }
        
        // Generate QR code URL
        $url = route('qr.scan', [
            'company' => $company->id,
            'branch' => $branch ? $branch->id : null
        ]);
        
        // Generate QR code
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($url);
            
        // Include branch name in the view if branch exists
        $branchName = $branch ? $branch->name : null;
        
        return view('companies.public-qr', compact('company', 'branch', 'qrCode', 'url', 'branchName'));
    }
    
    /**
     * Show the public branch QR code page (no authentication required)
     *
     * @param Company $company
     * @param Branch $branch
     * @return \Illuminate\View\View
     */
    public function showPublicBranchQrPage($companyId, $branchId)
    {
        // Manually find the company and branch
        $company = Company::findOrFail($companyId);
        $branch = Branch::where('company_id', $company->id)
                      ->findOrFail($branchId);
        
        // Generate QR code URL
        $url = route('qr.scan', [
            'company' => $company->id,
            'branch' => $branch->id
        ]);
        
        // Generate QR code
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($url);
            
        // Include branch name in the view
        $branchName = $branch->name;
        
        return view('companies.public-qr', compact('company', 'branch', 'qrCode', 'url', 'branchName'));
    }

    /**
     * Show the QR code page (admin only)
     *
     * @param Company $company
     * @return \Illuminate\View\View
     */
    public function showQrPage(Company $company)
    {
        $this->authorize('view', $company);
        
        // Get the branch if branch_id is provided
        $branch = null;
        if (request()->has('branch_id')) {
            $branch = Branch::where('company_id', $company->id)
                           ->findOrFail(request('branch_id'));
        }
        
        // Generate QR code URL
        $url = route('qr.scan', [
            'company' => $company->id,
            'branch' => $branch?->id
        ]);
        
        // Generate QR code
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($url);
        
        return view('companies.qr', compact('company', 'branch', 'qrCode', 'url'));
    }

    /**
     * Download QR code
     *
     * @param Company $company
     * @param Branch|null $branch
     * @return Response
     */
    public function downloadQrCode(Company $company, Branch $branch = null)
    {
        $this->authorize('view', $company);
        
        // Generate QR code URL
        $url = route('qr.scan', [
            'company' => $company->id,
            'branch' => $branch?->id
        ]);
        
        // Generate QR code
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($url);
        
        $filename = ($branch ? $branch->slug : $company->slug) . '-qrcode.svg';
        
        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
    public function index(Request $request)
    {
        $query = Company::with('branches')->latest();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('contact_number', 'like', '%' . $search . '%')
                  ->orWhereHas('branches', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $companies = $query->paginate(10)->withQueryString();
        
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
/**
 * Get branches for a company as JSON
 *
 * @param Company $company
 * @return \Illuminate\Http\JsonResponse
 */
public function getBranches(Company $company)
{
    $branches = $company->branches()
        ->select('id', 'name')
        ->orderBy('name')
        ->get()
        ->mapWithKeys(function ($branch) {
            return [$branch->id => $branch->name];
        });

    return response()->json($branches);
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
                'name'   => 'required|string|max:255|unique:companies,name',
                'email'  => 'nullable|email|unique:companies,email',
                'phone'  => 'nullable|string|max:32',
                'address' => 'required|string',
                'contact_number' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'gst_number' => 'nullable|string|max:255',
                'auto_approve_visitors' => 'sometimes|boolean',
                'face_recognition_enabled' => 'sometimes|boolean',
                'security_check_service' => 'sometimes|boolean',
                'security_checkin_type' => 'nullable|string|in:checkin,checkout,both',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('company_logos', 'public');
            }
            
            // Create new company
            $company = new Company($validated);
            $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');
            $company->face_recognition_enabled = $request->boolean('face_recognition_enabled');
            $company->security_check_service = $request->boolean('security_check_service');
            $company->security_checkin_type = $request->input('security_checkin_type');
            $company->contact_number = $validated['contact_number'] ?? '';
            $company->logo = $logoPath;
            $company->save();

            // Create default "Main Branch" for the company
            \App\Models\Branch::create([
                'company_id' => $company->id,
                'name' => 'Main Branch',
                'phone' => $validated['contact_number'] ?? '',
                'email' => $validated['email'] ?? '',
                'address' => $validated['address'] ?? '',
            ]);

            // Handle branches if provided
            $branches = $request->input('branches', []);
            if (!empty($branches) && is_array($branches)) {
                $names = array_values($branches['name'] ?? []);
                $phones = array_values($branches['phone'] ?? []);
                $emails = array_values($branches['email'] ?? []);
                $addresses = array_values($branches['address'] ?? []);
                $count = max(count($names), count($phones), count($emails), count($addresses));
                
                // Check for duplicate branch names
                $branchNames = [];
                for ($i = 0; $i < $count; $i++) {
                    $nm = trim((string)($names[$i] ?? ''));
                    if ($nm !== '') {
                        if (in_array(strtolower($nm), array_map('strtolower', $branchNames))) {
                            throw new \Exception('Duplicate branch name: ' . $nm);
                        }
                        $branchNames[] = $nm;
                    }
                }
                
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
            \Log::error('Company creation error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // For any other exceptions
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
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
            // Debug: Log incoming request data
            \Log::info('Company update request:', [
                'company_id' => $company->id,
                'request_data' => $request->all(),
                'security_check_service' => $request->input('security_check_service'),
                'security_checkin_type' => $request->input('security_checkin_type'),
            ]);
            
            // Validation rules
            $validated = $request->validate([
                'name'   => 'required|string|max:255|unique:companies,name,'.$company->id,
                'email'  => 'nullable|email|unique:companies,email,'.$company->id,
                'phone'  => 'nullable|string|max:32',
                'address' => 'required|string',
                'contact_number' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'gst_number' => 'nullable|string|max:255',
                'auto_approve_visitors' => 'sometimes|boolean',
                'face_recognition_enabled' => 'sometimes|boolean',
                'security_check_service' => 'sometimes|boolean',
                'security_checkin_type' => 'nullable|string|in:checkin,checkout,both',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($company->logo) {
                    Storage::disk('public')->delete($company->logo);
                }
                $company->logo = $request->file('logo')->store('company_logos', 'public');
            }
            
            // Update company attributes
            \Log::info('Before updating company attributes');
            $company->fill($validated);
            $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');
            $company->face_recognition_enabled = $request->boolean('face_recognition_enabled');
            $company->security_check_service = $request->boolean('security_check_service');
            $company->security_checkin_type = $request->input('security_checkin_type');
            
            \Log::info('About to save company', [
                'security_check_service' => $company->security_check_service,
                'security_checkin_type' => $company->security_checkin_type,
            ]);
            
            try {
                $company->save();
                \Log::info('Company saved successfully');
            } catch (\Exception $e) {
                \Log::error('Error saving company: ' . $e->getMessage(), [
                    'exception' => $e,
                    'company_data' => $company->toArray(),
                ]);
                throw $e;
            }

            \Log::info('About to process branches');

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
                $deleted = array_values($branches['deleted'] ?? []);

                \Log::info('Branch arrays:', [
                    'ids' => $ids,
                    'names' => $names,
                    'deleted' => $deleted
                ]);

                $count = max(
                    count($names), 
                    count($phones), 
                    count($emails), 
                    count($addresses), 
                    count($startTimes),
                    count($endTimes),
                    count($ids),
                    count($deleted)
                );
                
                for ($i = 0; $i < $count; $i++) {
                    $id = (int)($ids[$i] ?? 0);
                    $isDeleted = ($deleted[$i] ?? '0') === '1';
                    
                    \Log::info("Processing branch {$i}:", ['id' => $id, 'deleted' => $isDeleted]);
                    
                    if ($id > 0 && $isDeleted) {
                        // Delete marked branches
                        $branch = Branch::where('company_id', $company->id)->where('id', $id)->first();
                        if ($branch) {
                            \Log::info("Deleting branch {$id} (marked for deletion)");
                            $branch->delete();
                        }
                        continue;
                    }
                    
                    if ($isDeleted) {
                        // Skip new branches marked for deletion
                        continue;
                    }
                    
                    $name = trim((string)($names[$i] ?? ''));
                    $data = [
                        'name' => $name,
                        'phone' => (string)($phones[$i] ?? ''),
                        'email' => (string)($emails[$i] ?? ''),
                        'address' => (string)($addresses[$i] ?? ''),
                        'start_time' => !empty($startTimes[$i]) ? $startTimes[$i] : null,
                        'end_time' => !empty($endTimes[$i]) ? $endTimes[$i] : null,
                    ];

                    if ($id > 0) {
                        // Update existing branch
                        $branch = Branch::where('company_id', $company->id)->where('id', $id)->first();
                        if ($branch && $name !== '') {
                            \Log::info("Updating branch {$id}");
                            $branch->update($data);
                        }
                    } else {
                        // Create new branch if name is provided
                        if ($name !== '') {
                            \Log::info('Creating new branch:', $data);
                            Branch::create($data + ['company_id' => $company->id]);
                        }
                    }
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
            \Log::error('Company update error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($e instanceof \Illuminate\Database\QueryException) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) { // Duplicate entry error code
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'A company with this email already exists. Please use a different email address.');
                }
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the company: ' . $e->getMessage());
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
