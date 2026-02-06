<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Rule;

use App\Models\Company;
use App\Models\Branch;
use App\Models\Visitor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class QRManagementController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    /**
     * The authenticated user instance.
     *
     * @var \App\Models\User|\App\Models\CompanyUser
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
public function __construct()
{
    $this->middleware('auth')->except([
        'scan', 
        'createVisitor', 
        'storeVisitor',
        'showVisitForm',
        'showVisitFormWithBranch',
        'storeVisit',
        'storeVisitWithBranch',
        'storePublicVisit',
        'publicVisitorIndex',
        'show'
    ]);
}


    /**
     * Display a listing of companies with their QR codes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role === 'superadmin';
        
        $query = Company::query()
            ->when(!$isSuperAdmin, function($q) use ($user) {
                $q->where('id', $user->company_id);
            })
            ->orderBy('name');

        if (request()->has('search')) {
            $search = '%' . request('search') . '%';
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', $search)
                ->orWhere('email', 'like', $search)
                ->orWhere('phone', 'like', $search)
                ->orWhereHas('branches', function($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('phone', 'like', $search);
                });
            });
        }

        $companies = $query->get();
        
        // For company users, filter branches to only show assigned ones
        if (!$isSuperAdmin) {
            $userBranchIds = $user->branches()->pluck('branches.id')->toArray();
            
            $companies->each(function($company) use ($userBranchIds) {
                // Filter branches to only include those assigned to the user
                $company->setRelation('branches', 
                    $company->branches->filter(function($branch) use ($userBranchIds) {
                        return in_array($branch->id, $userBranchIds);
                    })
                );
            });
        } else {
            // For superadmin, load all branches
            $companies->load('branches');
        }

        return view('qr-management.index', compact('companies'));
    }



    /**
     * Display the QR code scanning page for public visitor registration.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Branch|null  $branch
     * @return \Illuminate\View\View
     */
    /**
     * Show the form for creating a new visitor.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function createVisitor(Company $company)
    {
        // Check if a specific branch was passed in the query string
        $branchId = request()->query('branch') ?? session('scanned_branch_id');
        $branchModel = null;
        
        if ($branchId) {
            $branchModel = $company->branches()->find($branchId);
            // Store branch ID in session for later use
            session(['scanned_branch_id' => $branchId]);
        }
        
        // Get the necessary data for the form
        $visitorCategories = $company->visitorCategories()->orderBy('name')->get();
        $departments = $company->departments()->orderBy('name')->get();
        $employees = $company->employees()->orderBy('name')->get();

        return view('visitors.public-create', [
            'company' => $company,
            'branch' => $branchModel,
            'visitorCategories' => $visitorCategories,
            'departments' => $departments,
            'employees' => $employees,
            'pageTitle' => 'New Visitor Registration - ' . $company->name
        ]);
    }

    public function storeVisitor(Company $company, Request $request)
{
    // Check if face recognition is enabled for this company
    $faceRecognitionEnabled = $company->face_recognition_enabled ?? false;
    
    // Build validation rules dynamically
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'visit_date' => 'nullable|date',
    ];
    
    // Only add face validation if face recognition is enabled
    if ($faceRecognitionEnabled) {
        $rules['face_image'] = 'required|string';
        $rules['face_encoding'] = 'required|string';
    }
    
    $validated = $request->validate($rules);

    try {
        $visitorData = [
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'visit_date' => $validated['visit_date'] ?? now()->format('Y-m-d'),
            'status' => 'Pending',
        ];
        
        // Only process face data if face recognition is enabled and data is provided
        if ($faceRecognitionEnabled && isset($validated['face_image'])) {
            // Process the image data (remove data:image/jpeg;base64, prefix if present)
            $imageData = $validated['face_image'];
            if (strpos($imageData, ';base64,') !== false) {
                $imageData = explode(';base64,', $imageData)[1];
            }
            
            // Save the image
            $imageName = 'visitor_photos/' . Str::random(40) . '.jpg';
            Storage::disk('public')->put($imageName, base64_decode($imageData));
            
            $visitorData['photo'] = $imageName;
            $visitorData['face_encoding'] = $validated['face_encoding'];
        }

        // Create visitor
        $visitor = Visitor::create($visitorData);

        // Note: Email will be sent after visit form completion with complete details
        
        // Check if branch was stored in session
        $branchId = session('scanned_branch_id');
        
        // Redirect to the next step
        $redirectUrl = route('public.visitor.visit.form', [
            'company' => $company->id,
            'visitor' => $visitor->id
        ]);
        
        if ($branchId) {
            $redirectUrl .= '?branch=' . $branchId;
        }
        
        return redirect($redirectUrl);

    } catch (\Exception $e) {
        \Log::error('Error saving visitor: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Error saving visitor: ' . $e->getMessage());
    }
}




/**
 * Show the visit form to complete visitor registration.
 *
 * @param  \App\Models\Company  $company
 * @param  \App\Models\Visitor  $visitor
 * @return \Illuminate\View\View
 */
public function showVisitForm(Company $company, \App\Models\Visitor $visitor)
{
    // Get necessary data for the form
    $visitorCategories = \App\Models\VisitorCategory::where('company_id', $company->id)->get();
    $departments = \App\Models\Department::where('company_id', $company->id)->get();
    $employees = \App\Models\Employee::where('company_id', $company->id)->get();
    
    // Check if a specific branch was passed in the request or session
    $branchId = request()->route('branch') ?? request()->query('branch') ?? session('scanned_branch_id');
    $branchModel = null;
    
    if ($branchId) {
        $branchModel = $company->branches()->find($branchId);
        $branches = $branchModel ? collect([$branchModel]) : $company->branches;
    } else {
        $branches = $company->branches;
    }

    return view('visitors.public-visit', [
        'company' => $company,
        'visitor' => $visitor,
        'branch' => $branchModel,
        'visitorCategories' => $visitorCategories,
        'departments' => $departments,
        'employees' => $employees,
        'branches' => $branches,
        'pageTitle' => 'Complete Visit Details - ' . $company->name
    ]);
}

/**
 * Store the visit details for a visitor.
 *
 * @param  \App\Models\Company  $company
 * @param  \App\Models\Visitor  $visitor
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function storeVisit(Company $company, \App\Models\Visitor $visitor, \Illuminate\Http\Request $request)
{
    // Debug: Log incoming request data
    \Log::info('Store Visit Request:', [
        'company_id' => $company->id,
        'visitor_id' => $visitor->id,
        'request_data' => $request->all(),
        'is_ajax' => $request->ajax()
    ]);

    try {
        // First, validate required fields
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'purpose' => 'required|string',
            'visitor_company' => 'nullable|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'visit_date' => 'nullable|date',
        ]);
        
        // Update visitor with visit details
        $visitor->fill([
            'department_id'        => $validated['department_id'],
            'purpose'              => $validated['purpose'],
            'visit_date'           => $validated['visit_date'] ?? $visitor->visit_date ?? now()->format('Y-m-d'),
            'status'               => ($visitor->status === 'Approved' || $company->auto_approve_visitors) ? 'Approved' : 'Pending',
            'approved_at'          => ($visitor->status === 'Approved' ? $visitor->approved_at : ($company->auto_approve_visitors ? now() : null)),
            'visitor_company'      => $validated['visitor_company'] ?? null,
            'branch_id'            => $validated['branch_id'] ?? null,
            'visitor_category_id'  => $request->input('visitor_category_id') ?: null,
            'person_to_visit'      => $request->input('person_to_visit') ?: null,
            'vehicle_type'         => $request->input('vehicle_type') ?: null,
            'vehicle_number'       => $request->input('vehicle_number') ?: null,
            'goods_in_car'         => $request->input('goods_in_car') ?: null,
            'visitor_website'      => $request->input('visitor_website') ?: null,
            'workman_policy_photo' => $request->hasFile('workman_policy_photo') ? $visitor->workman_policy_photo : $visitor->workman_policy_photo,
            'updated_at'           => now()
        ]);
        
        $visitor->save();

        // Send email notification with complete visit details
        try {
            if (!empty($visitor->email)) {
                // Load relationships for email display
                $visitor->load(['company', 'branch', 'department']);
                \App\Jobs\SendVisitorEmail::dispatchSync(new \App\Mail\VisitorCreatedMail($visitor), $visitor->email);
            }
        } catch (\Throwable $e) {
            \Log::warning('Visit details email dispatch failed: '.$e->getMessage());
        }

        // Send approval email if visitor was auto-approved or status changed to approved
        try {
            if ($visitor->status === 'Approved' && !empty($visitor->email)) {
                \App\Jobs\SendVisitorEmail::dispatchSync(new \App\Mail\VisitorApprovedMail($visitor), $visitor->email);
            }
        } catch (\Throwable $e) {
            \Log::warning('VisitorApproved mail dispatch failed: '.$e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Visit details updated successfully!',
                'redirect' => route('public.visitor.show', ['company' => $company->id, 'visitor' => $visitor->id])
            ]);
        }

        return redirect()->route('public.visitor.show', ['company' => $company->id, 'visitor' => $visitor->id])
            ->with('success', 'Visit details updated successfully!')
            ->with('show_pass_button', true);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error saving visit details: ' . $e->getMessage());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }
        
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        \Log::error('Error saving visit details: ' . $e->getMessage());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the visit details. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'An error occurred while saving the visit details. Please try again.')
            ->withInput();
    }
}

    /**
 * Display the public visitor index page.
 *
 * @param  \App\Models\Company  $company
 * @param  int|string  $visitor  Visitor ID or 'create' to show creation form
 * @param  int|null  $branch  Branch ID (optional)
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
public function publicVisitorIndex(Company $company, $visitor = null, $branch = null)
{
    try {
        // Debug: Log the incoming request
        \Log::info('publicVisitorIndex called:', [
            'company_id' => $company->id,
            'visitor_param' => $visitor,
            'branch_param' => $branch,
            'route_name' => request()->route()->getName(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'user_authenticated' => auth()->check(),
            'company_authenticated' => auth()->guard('company')->check()
        ]);
        
        // If visitor is 'create', show the creation form
        if ($visitor === 'create') {
            return $this->createVisitor($company);
        }

        // Find the visitor with relationships
        $visitor = \App\Models\Visitor::with(['department', 'branch', 'category'])
            ->findOrFail($visitor);
        
        // Verify visitor belongs to company
        if ($visitor->company_id != $company->id) {
            \Log::error('Visitor company mismatch in publicVisitorIndex:', [
                'visitor_id' => $visitor->id,
                'visitor_company_id' => $visitor->company_id,
                'route_company_id' => $company->id
            ]);
            
            // Return to QR scan page with error message
            return redirect()->route('qr.scan', ['company' => $company->id])
                ->with('error', 'This visitor belongs to a different company. Please scan the correct QR code for company: ' . $visitor->company->name);
        }

        \Log::info("Displaying public visitor index for visitor ID: " . $visitor->id);
        
        // Get necessary data for the view
        $visitorCategories = $company->visitorCategories()->orderBy('name')->get();
        $departments = $company->departments()->orderBy('name')->get();
        $employees = $company->employees()->orderBy('name')->get();
        
        // Handle branch - check route parameter, query parameter, or session
        $branchId = $branch ?? request()->route('branch') ?? request()->query('branch') ?? session('scanned_branch_id');
        $branchModel = null;
        
        if ($branchId) {
            $branchModel = $company->branches()->find($branchId);
            $branches = $branchModel ? collect([$branchModel]) : $company->branches;
            // Store branch ID in session for later use
            session(['scanned_branch_id' => $branchId]);
        } else {
            $branches = $company->branches;
        }
        
        return view('visitors.public-index', [
            'company' => $company,
            'visitor' => $visitor,
            'branch' => $branchModel,
            'visitorCategories' => $visitorCategories,
            'departments' => $departments,
            'employees' => $employees,
            'branches' => $branches,
            'pageTitle' => 'Complete Your Visit - ' . $company->name
        ]);

    } catch (\Exception $e) {
        \Log::error('Error in publicVisitorIndex: ' . $e->getMessage());
        return redirect()->route('qr.visitor.create', ['company' => $company->id])
            ->with('error', 'Could not load visitor details. ' . $e->getMessage());
    }
}


    /**
     * Display the QR code scanning page for public visitor registration.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Branch|null  $branch
     * @return \Illuminate\View\View
     */
    public function scan(Company $company, $branch = null)
{
    $branchModel = null;
    if ($branch) {
        $branchModel = $company->branches()->find($branch);
        // Store branch ID in session for later use
        session(['scanned_branch_id' => $branch]);
    }
    
    // Get visitor from session if exists
    $visitor = null;
    if (session('current_visitor_id')) {
        $visitor = \App\Models\Visitor::find(session('current_visitor_id'));
    }
    
    // Get necessary data for the form
    $visitorCategories = $company->visitorCategories;
    $departments = $company->departments;
    $employees = $company->employees;

    return view('visitors.public-index', [
        'company' => $company,
        'visitor' => $visitor,
        'branch' => $branchModel, // Pass the model, not the ID
        'visitorCategories' => $visitorCategories,
        'departments' => $departments,
        'employees' => $employees
    ]);
}

    /**
     * Display the QR code for a specific company or branch.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Branch|null  $branch
     * @return \Illuminate\View\View
     */
    public function show(Company $company, Branch $branch = null)
    {
        // Check if a specific branch was requested via query parameter (for public access)
        $branchId = request()->query('branch_id');
        if (!$branch && $branchId) {
            $branch = $company->branches()->find($branchId);
        }
        
        // Check if user is authenticated
        $isAuthenticated = auth()->check();
        
        if ($isAuthenticated) {
            // Authenticated user - show admin QR management view
            $user = auth()->user();
            $isSuperAdmin = $user->is_super_admin || $user->hasRole('super_admin') || $user->role === 'superadmin';
            
            // Check if user has access to this company
            if (!$isSuperAdmin && $user->company_id != $company->id) {
                abort(403, 'You do not have access to this company\'s QR code.');
            }
            
            // If branch is provided, ensure it belongs to the company
            if ($branch && $branch->company_id != $company->id) {
                abort(404, 'Branch not found for this company.');
            }
            
            // Load company data with relationships
            $company->loadCount('branches', 'departments')
                   ->load(['branches' => function($query) {
                       $query->orderBy('name');
                   }]);
            
            // Generate QR code URL
            $qrUrl = url('/qr/scan/' . $company->id);
            
            // If branch is provided, add it to the URL
            if ($branch) {
                $qrUrl .= '/' . $branch->id;
            }
            
            // Generate QR code with the URL
            $qrCodeSvg = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrUrl);
                
            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            
            return view('qr-management.show', [
                'company' => $company,
                'branch' => $branch,
                'qrCode' => $qrCodeBase64,
                'isSuperAdmin' => $isSuperAdmin
            ]);
        } else {
            // Public access - show public QR code view
            $branchName = $branch ? $branch->name : null;
            
            // Generate the URL for the QR code
            if ($branch) {
                $url = route('qr.scan', ['company' => $company->id, 'branch' => $branch->id]);
            } else {
                $url = route('qr.scan', ['company' => $company->id]);
            }
            
            // Generate QR code
            $qrCode = QrCode::size(300)
                ->margin(2)
                ->generate($url);
            
            return view('companies.public-qr', compact('company', 'qrCode', 'url', 'branchName'));
        }
    }

    /**
     * Download the QR code for a company or branch.
     */
    public function download(Company $company, Branch $branch = null)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->is_super_admin || $user->hasRole('super_admin') || $user->role === 'superadmin';
        
        // Check if user has access to this company
        if (!$isSuperAdmin && $user->company_id != $company->id) {
            abort(403, 'You do not have permission to download this QR code.');
        } 
        
        // If branch is provided, ensure it belongs to the company
        // Using loose comparison (!=) to handle potential string/int mismatches
        if ($branch && $branch->company_id != $company->id) {
            abort(404, 'Branch not found for this company.');
        }
        
        // Generate QR code URL
        $qrUrl = url('/qr/scan/' . $company->id);
        
        // If branch is provided, add it to the URL and set the filename
        if ($branch) {
            $qrUrl .= '/' . $branch->id;
            $filename = "qr-code-{$company->name}-{$branch->name}.svg"
                ?: 'qr-code-branch.svg';
        } else {
            $filename = "qr-code-{$company->name}.svg"
                ?: 'qr-code-company.svg';
        }
        
        // Sanitize filename
        $filename = preg_replace('/[^a-z0-9-_.]/i', '-', $filename);
        

        // Generate QR code with the URL
        $qrCode = QrCode::format('svg')
            ->size(1000)
            ->errorCorrection('H')
            ->generate($qrUrl);

        return response($qrCode, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");

    }

    /**
     * Display the QR code scanner interface for the authenticated company user.
     */
    public function scanner()
    {
        try {
            $user = $this->user;
            $isSuperAdmin = $user->hasRole('super_admin');
            
            if (!$isSuperAdmin && !$user->company_id) {
                abort(403, 'Access denied. Company user required.');
            }
            
            // Get companies based on user role
            $query = Company::with(['branches', 'departments']);
            
            if (!$isSuperAdmin) {
                $query->where('id', $user->company_id);
            }
            
            $companies = $query->get();
            
            // For backward compatibility, set the first company as default
            $company = $companies->first();
            
            // Generate QR data for the company
            $qrData = [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'branches' => $company->branches->pluck('name')->toArray(),
                'departments' => $company->departments->pluck('name')->toArray(),
                'scanned_at' => now()->toDateTimeString(),
            ];
            
            // Generate QR code as SVG (doesn't require imagick)
            $qrCodeSvg = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate(json_encode($qrData));
            
            // Store both the raw SVG and a base64 version for different uses
            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            
            // Clean up the SVG to ensure it's properly formatted
            $qrCodeSvg = preg_replace('/<\?xml.*?\?>/', '', $qrCodeSvg);
            $qrCodeSvg = trim($qrCodeSvg);
            
            // Pass data to the view
            return view('qr.scanner', [
                'company' => $company,
                'companies' => $companies,
                'isSuperAdmin' => $isSuperAdmin,
                'qrCode' => $qrCodeBase64
            ]);
            $qrCodeSvg = preg_replace('/<\?xml.*?\?>/', '', $qrCodeSvg);
            $qrCodeSvg = trim($qrCodeSvg);
            
            Log::info('QR Scanner accessed', [
                'user_id' => $user->id,
                'company_id' => $company->id,
                'company_name' => $company->name
            ]);
            
            return view('qr.scanner', [
                'company' => $company,
                'qrCode' => $qrCodeBase64,
                'qrData' => $qrData
            ]);
            
        } catch (\Exception $e) {
            Log::error('QR Scanner Access Error: ' . $e->getMessage(), [
                'user_id' => $this->user->id ?? null,
                'company_id' => $this->user->company_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error accessing QR Scanner: ' . $e->getMessage());
        }
    }

    /**
     * Get QR code data for a specific company
     */
    public function getCompanyQrData(Company $company)
    {
        try {
            $company->load(['branches', 'departments']);
            
            // Generate QR data for the company
            $qrData = [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'branches' => $company->branches->pluck('name')->toArray(),
                'departments' => $company->departments->pluck('name')->toArray(),
                'scanned_at' => now()->toDateTimeString(),
            ];
            
            // Generate QR code as SVG
            $qrCodeSvg = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate(json_encode($qrData));
            
            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            
            return response()->json([
                'success' => true,
                'qrCode' => $qrCodeBase64,
                'company' => [
                    'name' => $company->name,
                    'branches_count' => $company->branches->count(),
                    'departments_count' => $company->departments->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process a scanned QR code.
     */
    public function processScan(Request $request)
    {
        try {
            $user = $this->user;
            
            // Ensure the user is a company user
            if (!$user || !$user->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Company user required.',
                ], 403);
            }
            
            $request->validate([
                'qr_data' => 'required|string',
            ]);

            $qrData = $request->input('qr_data');
            Log::info('QR Code Scanned', [
                'user_id' => $user->id,
                'company_id' => $user->company_id,
                'qr_data' => $qrData
            ]);
            
            // Process the QR code data
            $visitorData = json_decode($qrData, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid QR code data');
            }
            
            // Verify the visitor belongs to the same company as the user
            if (isset($visitorData['visitor_id'])) {
                $visitor = Visitor::where('id', $visitorData['visitor_id'])
                    ->where('company_id', $user->company_id)
                    ->with(['company', 'department'])
                    ->first();
                    
                if (!$visitor) {
                    Log::warning('Visitor not found or access denied', [
                        'user_id' => $user->id,
                        'company_id' => $user->company_id,
                        'visitor_id' => $visitorData['visitor_id'] ?? null,
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Visitor not found or access denied',
                    ], 403);
                }
                
                // Log successful scan
                Log::info('Visitor QR Scanned', [
                    'user_id' => $user->id,
                    'company_id' => $user->company_id,
                    'visitor_id' => $visitor->id,
                    'visitor_name' => $visitor->name
                ]);
                
                // Process the visitor check-in/out logic here
                // ...
                
                return response()->json([
                    'success' => true,
                    'message' => 'Visitor verified successfully',
                    'visitor' => [
                        'id' => $visitor->id,
                        'name' => $visitor->name,
                        'email' => $visitor->email,
                        'phone' => $visitor->phone,
                        'company' => $visitor->company->name,
                        'department' => $visitor->department->name ?? 'N/A',
                        'purpose' => $visitor->purpose,
                        'status' => $visitor->status,
                    ],
                ]);
            }
            
            // For other types of QR codes (not visitor-specific)
            return response()->json([
                'success' => true,
                'message' => 'QR code scanned successfully',
                'data' => $visitorData,
            ]);
            
        } catch (\Exception $e) {
            Log::error('QR Scan Error: ' . $e->getMessage(), [
                'user_id' => $this->user->id ?? null,
                'company_id' => $this->user->company_id ?? null,
                'qr_data' => $request->input('qr_data')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing QR code: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the visit form with branch context (adapter for route with branch param).
     */
    public function showVisitFormWithBranch(Company $company, $branchId, \App\Models\Visitor $visitor)
    {
        // Set the branch in session/request so the main method can pick it up
        request()->merge(['branch' => $branchId]);
        session(['scanned_branch_id' => $branchId]);
        
        return $this->showVisitForm($company, $visitor);
    }

    /**
     * Store request with branch context (adapter for route with branch param).
     */
    public function storeVisitWithBranch(Company $company, $branchId, \App\Models\Visitor $visitor, \Illuminate\Http\Request $request)
    {
        // Set the branch in request so validation/logic works
        $request->merge(['branch_id' => $branchId]);
        
        return $this->storeVisit($company, $visitor, $request);
    }
}
