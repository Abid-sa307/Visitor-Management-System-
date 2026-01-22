<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name')->paginate(10);
        return view('companies.index', compact('companies'));
    }

    /**
     * Display a focused view of all branches under a company.
     */
    public function branches(Company $company)
    {
        $branches = $company->branches()->orderBy('name')->get();
        return view('companies.branches', compact('company', 'branches'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        try {
            // Full validation including advanced features
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
                'mail_service_enabled' => 'sometimes|boolean',
                'visitor_notifications_enabled' => 'sometimes|boolean',
                'enable_visitor_notifications' => 'sometimes|boolean',
                'mark_in_out_in_qr_flow' => 'sometimes|boolean',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('company_logos', 'public');
            }
            
            // Create new company with all features
            $company = new Company($validated);
            $company->logo = $logoPath;
            $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');
            $company->face_recognition_enabled = $request->boolean('face_recognition_enabled');
            $company->security_check_service = $request->boolean('security_check_service');
            $company->mail_service_enabled = $request->boolean('mail_service_enabled');
            $company->visitor_notifications_enabled = $request->boolean('visitor_notifications_enabled');
            $company->enable_visitor_notifications = $request->boolean('enable_visitor_notifications');
            $company->mark_in_out_in_qr_flow = $request->boolean('mark_in_out_in_qr_flow');
            
            // Handle security_checkin_type
            if ($request->boolean('security_check_service')) {
                $company->security_checkin_type = $request->input('security_checkin_type_hidden') ?: $request->input('security_checkin_type') ?: 'both';
            } else {
                $company->security_checkin_type = 'both';
            }
            
            $company->contact_number = $validated['contact_number'] ?? '';
            $company->save();

            // Create default "Main Branch" for the company
            $mainBranch = Branch::create([
                'company_id' => $company->id,
                'name' => 'Main Branch',
                'phone' => $validated['contact_number'] ?? '',
                'email' => $validated['email'] ?? '',
                'address' => $validated['address'] ?? '',
            ]);

            // Handle branches from form (including Main Branch update)
            $branches = $request->input('branches', []);
            if (!empty($branches) && is_array($branches)) {
                $names = array_values($branches['name'] ?? []);
                $phones = array_values($branches['phone'] ?? []);
                $emails = array_values($branches['email'] ?? []);
                $addresses = array_values($branches['address'] ?? []);
                $start_times = array_values($branches['start_time'] ?? []);
                $end_times = array_values($branches['end_time'] ?? []);
                $count = max(count($names), count($phones), count($emails), count($addresses), count($start_times), count($end_times));
                
                // Check for duplicate branch names within the same company
                $branchNames = [];
                $mainBranchUpdated = false;
                
                for ($i = 0; $i < $count; $i++) {
                    $nm = trim((string)($names[$i] ?? ''));
                    
                    if ($nm !== '') {
                        // Check if this is the Main Branch row (first row or explicitly named "Main Branch")
                        if ($i === 0 || strtolower($nm) === 'main branch') {
                            // Update Main Branch with form data
                            $mainBranch->update([
                                'name' => $nm,
                                'phone' => $phones[$i] ?? '',
                                'email' => $emails[$i] ?? '',
                                'address' => $addresses[$i] ?? '',
                                'start_time' => !empty($start_times[$i]) ? $start_times[$i] : null,
                                'end_time' => !empty($end_times[$i]) ? $end_times[$i] : null,
                            ]);
                            $mainBranchUpdated = true;
                            $branchNames[] = strtolower($nm);
                            continue;
                        }
                        
                        if (in_array(strtolower($nm), array_map('strtolower', $branchNames))) {
                            return redirect()
                                ->back()
                                ->withInput()
                                ->with('error', "Branch name '{$nm}' is already used. Each branch must have a unique name within the company.");
                        }
                        $branchNames[] = strtolower($nm);
                    }
                }
                
                // Process additional branches (skip first if it was Main Branch)
                $startIndex = $mainBranchUpdated ? 1 : 0;
                for ($i = $startIndex; $i < $count; $i++) {
                    $nm = trim((string)($names[$i] ?? ''));
                    $ph = (string)($phones[$i] ?? '');
                    $em = (string)($emails[$i] ?? '');
                    $ad = (string)($addresses[$i] ?? '');
                    
                    if ($nm === '' && $ph === '' && $em === '' && $ad === '') continue;
                    
                    Branch::create([
                        'company_id' => $company->id,
                        'name' => $nm ?: 'Branch ' . ($i + 1),
                        'phone' => $ph,
                        'email' => $em,
                        'address' => $ad,
                        'start_time' => !empty($start_times[$i]) ? $start_times[$i] : null,
                        'end_time' => !empty($end_times[$i]) ? $end_times[$i] : null,
                    ]);
                }
            }

            return redirect()
                ->route('companies.index')
                ->with('success', 'Company created successfully.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        try {
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
                'mail_service_enabled' => 'sometimes|boolean',
                'visitor_notifications_enabled' => 'sometimes|boolean',
                'enable_visitor_notifications' => 'sometimes|boolean',
                'mark_in_out_in_qr_flow' => 'sometimes|boolean',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $logoPath = $company->logo;
            if ($request->hasFile('logo')) {
                if ($company->logo) {
                    Storage::disk('public')->delete($company->logo);
                }
                $logoPath = $request->file('logo')->store('company_logos', 'public');
            }
            
            $company->fill($validated);
            $company->logo = $logoPath;
            $company->auto_approve_visitors = $request->boolean('auto_approve_visitors');
            $company->face_recognition_enabled = $request->boolean('face_recognition_enabled');
            $company->security_check_service = $request->boolean('security_check_service');
            $company->mail_service_enabled = $request->boolean('mail_service_enabled');
            $company->visitor_notifications_enabled = $request->boolean('visitor_notifications_enabled');
            $company->enable_visitor_notifications = $request->boolean('enable_visitor_notifications');
            $company->mark_in_out_in_qr_flow = $request->boolean('mark_in_out_in_qr_flow');
            
            // Handle security_checkin_type
            if ($request->boolean('security_check_service')) {
                $company->security_checkin_type = $request->input('security_checkin_type_hidden') ?: $request->input('security_checkin_type') ?: 'both';
            } else {
                $company->security_checkin_type = 'both';
            }
            
            $company->contact_number = $validated['contact_number'] ?? '';
            $company->save();

            // Handle branches update
            $branches = $request->input('branches', []);
            if (!empty($branches) && is_array($branches)) {
                $ids = $branches['id'] ?? [];
                $names = $branches['name'] ?? [];
                $phones = $branches['phone'] ?? [];
                $emails = $branches['email'] ?? [];
                $addresses = $branches['address'] ?? [];
                $start_times = $branches['start_time'] ?? [];
                $end_times = $branches['end_time'] ?? [];
                $deleted = $branches['deleted'] ?? [];
                
                $count = count($names);
                
                // Get the main branch (first branch created for this company)
                $mainBranchId = $company->branches()->orderBy('id')->value('id');
                
                // Check for duplicate branch names
                $branchNames = [];
                for ($i = 0; $i < $count; $i++) {
                    $nm = trim((string)($names[$i] ?? ''));
                    $isDeleted = ($deleted[$i] ?? '0') === '1';
                    $branchId = isset($ids[$i]) && $ids[$i] !== '' && $ids[$i] !== '0' ? (int)$ids[$i] : null;
                    
                    if ($nm !== '' && !$isDeleted) {
                        // Check for duplicates, but skip if it's the same branch being updated
                        foreach ($branchNames as $existingName => $existingId) {
                            if (strtolower($nm) === strtolower($existingName) && $branchId !== $existingId) {
                                return redirect()
                                    ->back()
                                    ->withInput()
                                    ->with('error', "Branch name '{$nm}' is already used. Each branch must have a unique name within the company.");
                            }
                        }
                        $branchNames[$nm] = $branchId;
                    }
                }
                
                for ($i = 0; $i < $count; $i++) {
                    $nm = trim((string)($names[$i] ?? ''));
                    if ($nm === '') continue;
                    
                    $branchId = isset($ids[$i]) && $ids[$i] !== '' && $ids[$i] !== '0' ? (int)$ids[$i] : null;
                    $isDeleted = ($deleted[$i] ?? '0') === '1';
                    
                    if ($isDeleted && $branchId) {
                        // Prevent deletion of main branch
                        if ($branchId === $mainBranchId) {
                            continue;
                        }
                        $branch = Branch::find($branchId);
                        if ($branch && $branch->company_id === $company->id) {
                            $branch->delete();
                        }
                        continue;
                    }
                    
                    $branchData = [
                        'name' => $nm,
                        'phone' => $phones[$i] ?? '',
                        'email' => $emails[$i] ?? '',
                        'address' => $addresses[$i] ?? '',
                        'start_time' => !empty($start_times[$i]) ? $start_times[$i] : null,
                        'end_time' => !empty($end_times[$i]) ? $end_times[$i] : null,
                    ];
                    
                    if ($branchId) {
                        $branch = Branch::where('id', $branchId)
                                      ->where('company_id', $company->id)
                                      ->first();
                        if ($branch) {
                            $branch->update($branchData);
                        }
                    } else {
                        $branchData['company_id'] = $company->id;
                        Branch::create($branchData);
                    }
                }
            }

            return redirect()
                ->route('companies.index')
                ->with('success', 'Company updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    /**
     * Show the public QR code page (no authentication required)
     *
     * @param int $companyId
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
     * @param int $companyId
     * @param int $branchId
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
     * Get branches for a company (AJAX endpoint)
     */
    public function getBranches(Company $company)
    {
        try {
            $user = auth()->user();
            
            // If not superadmin, ensure they can only access their own company's branches
            if (!in_array($user->role, ['superadmin', 'super_admin'])) {
                if ($company->id !== $user->company_id) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
            }
            
            $branches = $company->branches()
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
            
            return response()->json($branches);
        } catch (\Exception $e) {
            \Log::error('Error fetching branches: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load branches',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
