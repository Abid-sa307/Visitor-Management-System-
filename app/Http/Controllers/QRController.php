<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Visitor;
use App\Models\VisitorCategory;
use App\Models\Department;
use App\Services\GoogleNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.guest';
    public function scan(Company $company, $branch = null, Request $request = null)
    {
        $request = $request ?? request();
        $branchModel = $branch ? $company->branches()->find($branch) : null;

        // Check if current time is within branch operation hours (only if no alert already shown)
        if ($branchModel && !session('alert')) {
            $currentTime = now()->format('H:i');
            
            // Only process if both start_time and end_time are actually set
            if (!empty($branchModel->start_time) && !empty($branchModel->end_time)) {
                $startTime = date('H:i', strtotime($branchModel->start_time));
                $endTime = date('H:i', strtotime($branchModel->end_time));
                
                // Debug logging
                \Log::info('Operation Hours Check:', [
                    'branch_id' => $branch,
                    'branch_name' => $branchModel->name,
                    'current_time' => $currentTime,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'current_time_numeric' => (int)str_replace(':', '', $currentTime),
                    'start_time_numeric' => (int)str_replace(':', '', $startTime),
                    'end_time_numeric' => (int)str_replace(':', '', $endTime),
                    'has_hours' => true
                ]);
                
                if ($currentTime < $startTime || $currentTime > $endTime) {
                    $errorMessage = "Visitor cannot be added before or after operational time. Branch operating hours are {$startTime} to {$endTime}.";
                    
                    \Log::info('Outside operating hours - setting alert');
                    
                    // Set alert in session without redirecting
                    session()->flash('alert', $errorMessage);
                    session()->flash('error', $errorMessage);
                } else {
                    \Log::info('Within operating hours - no alert needed');
                }
            } else {
                \Log::info('No operation hours set for branch - skipping validation');
            }
        }

        // Get visitor from session
        $visitor = null;
        if (session('current_visitor_id')) {
            $visitor = Visitor::find(session('current_visitor_id'));
            
            // Check if visitor exists and is checked out
            if ($visitor && $visitor->status === 'Completed' && $visitor->out_time) {
                // Clear the visitor from session
                $request->session()->forget('current_visitor_id');
                $visitor = null;
            } elseif (!$visitor) {
                // If visitor not found, clear the session
                $request->session()->forget('current_visitor_id');
            }
        }

        return view('visitors.public-index', [
            'company' => $company,
            'branch' => $branchModel,
            'visitor' => $visitor
        ]);
    }

    /**
     * Show the form for creating a new visitor
     */
    public function createVisitor(Company $company, $branch = null)
    {
        $branchModel = null;
        if ($branch) {
            $branchModel = $company->branches()->find($branch);
            
            // Check if current time is within branch operation hours
            if ($branchModel) {
                // Only check if branch has both start_time and end_time set
                if (!empty($branchModel->start_time) && !empty($branchModel->end_time)) {
                    $currentTime = now()->format('H:i');
                    $startTime = date('H:i', strtotime($branchModel->start_time));
                    $endTime = date('H:i', strtotime($branchModel->end_time));
                    
                    if ($currentTime < $startTime || $currentTime > $endTime) {
                        $redirectParams = $branch ? ['company' => $company->id, 'branch' => $branch] : ['company' => $company->id];
                        $errorMessage = "Visitor cannot be added before or after operational time. Branch operating hours are {$startTime} to {$endTime}.";
                        
                        return redirect()->route('qr.scan', $redirectParams)
                            ->with('error', $errorMessage)
                            ->with('alert', $errorMessage);
                    }
                }
            }
        }
        
        return view('visitors.public-create', [
            'company' => $company,
            'branch' => $branchModel
        ]);
    }

    /**
     * Show the visit form for existing visitors
     */
    public function showVisitForm(Company $company, $branch = null)
    {
        $branchModel = null;
        if ($branch) {
            $branchModel = $company->branches()->find($branch);
        }
        
        // Get departments for the company
        $departments = $company->departments()->get();
        
        // If a specific branch QR was scanned, only show that branch
        // Otherwise show all branches of the company
        if ($branchModel) {
            $branches = collect([$branchModel]); // Only the scanned branch
        } else {
            $branches = $company->branches()->get(); // All branches
        }
        
        return view('visitors.public-visit', [
            'company' => $company,
            'branch' => $branchModel,
            'departments' => $departments,
            'branches' => $branches,
            'visitor' => new \App\Models\Visitor() // Add empty visitor to prevent undefined variable errors
        ]);
    }

    /**
     * Download QR code for a company
     */
    public function downloadQR(Company $company)
    {
        // Get the base URL
        $url = route('qr.scan', $company);
        
        // Get branch information if available
        $branch = request()->has('branch_id') 
            ? \App\Models\Branch::find(request('branch_id'))
            : null;
        
        // Create human-readable text for QR code
        $qrText = "Company: " . $company->name . "\n";
        $qrText .= "Contact: " . $company->contact_number . "\n";
        $qrText .= "Email: " . $company->email . "\n";
        
        if ($branch) {
            $qrText .= "\nBranch: " . $branch->name . "\n";
            $qrText .= "Address: " . $branch->address . "\n";
            $qrText .= "Contact: " . $branch->phone . "\n";
        }
        
        // Add the URL at the end
        $qrText .= "\nScan to check-in: " . $url;
        
        // Create a renderer with SVG backend
        $renderer = new \BaconQrCode\Renderer\Image\SvgImageBackEnd();
        $image = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(300),
            $renderer
        );
        
        // Create writer and generate QR code with the formatted text
        $writer = new \BaconQrCode\Writer($image);
        $qrCode = $writer->writeString($qrText);
        
        // Generate filename with company (and branch if available)
        $filename = \Illuminate\Support\Str::slug($company->name);
        if ($branch) {
            $filename .= '-' . \Illuminate\Support\Str::slug($branch->name);
        }
        $filename .= '-qrcode.svg';
        
        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
    
    /**
     * Store a newly created visitor in storage.
     */
    public function storeVisitor(Request $request, Company $company, $branch = null)
    {
        // Check if current time is within branch operation hours
        if ($branch) {
            $branchModel = $company->branches()->find($branch);
            if ($branchModel) {
                // Only check if branch has both start_time and end_time set
                if (!empty($branchModel->start_time) && !empty($branchModel->end_time)) {
                    $currentTime = now()->format('H:i');
                    $startTime = date('H:i', strtotime($branchModel->start_time));
                    $endTime = date('H:i', strtotime($branchModel->end_time));
                    
                    if ($currentTime < $startTime || $currentTime > $endTime) {
                        $redirectParams = $branch ? ['company' => $company->id, 'branch' => $branch] : ['company' => $company->id];
                        $errorMessage = "Visitor cannot be added before or after operational time. Branch operating hours are {$startTime} to {$endTime}.";
                        
                        return redirect()->route('qr.scan', $redirectParams)
                            ->with('error', $errorMessage)
                            ->with('alert', $errorMessage);
                    }
                }
            }
        }

        // Check if face recognition is enabled for this company
        $faceRecognitionEnabled = $company->face_recognition_enabled ?? false;
        
        // Build validation rules dynamically
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|numeric|digits_between:10,15',
            'purpose' => 'nullable|string|max:1000',
            'visit_date' => 'nullable|date|after_or_equal:today',
            'documents.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            'document' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
        ];
        
        // Only add face validation if face recognition is enabled
        if ($faceRecognitionEnabled) {
            $rules['face_encoding'] = 'required';
            $rules['face_image'] = 'required';
        } else {
            $rules['face_encoding'] = 'nullable';
            $rules['face_image'] = 'nullable';
        }
        
        // Validate the request
        $validated = $request->validate($rules);
        
        // Set default values for required fields if they're empty
        $validated['name'] = $validated['name'] ?? 'Guest Visitor';
        $validated['phone'] = $validated['phone'] ?? 'Not Provided';
        $validated['purpose'] = $validated['purpose'] ?? 'General Visit';
        
        try {
            // Create visitor with only the provided fields
            $visitorData = [
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'purpose' => $validated['purpose'],
                'visit_date' => $validated['visit_date'] ?? now()->format('Y-m-d'),
                'company_id' => $company->id,
                'is_approved' => $company->auto_approve_visitors,
            ];
            
            // Set status based on auto approval
            if ($company->auto_approve_visitors) {
                $visitorData['status'] = 'Approved';
                $visitorData['approved_at'] = now();
                // Don't set visit_completed_at here - only set when form is actually completed
            } else {
                $visitorData['status'] = 'Pending';
            }
            
            // Add branch_id if branch was specified in QR scan
            if ($branch) {
                $branchModel = $company->branches()->find($branch);
                if ($branchModel) {
                    $visitorData['branch_id'] = $branchModel->id;
                }
            }
            
            // Only add face_encoding if it exists
            if (!empty($validated['face_encoding'])) {
                $visitorData['face_encoding'] = $validated['face_encoding'];
            }
            
            $visitor = new Visitor($visitorData);
            
            // Handle face image
            if (!empty($validated['face_image'])) {
                $imageData = $validated['face_image'];
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    
                    if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                        throw new \Exception('Invalid image type');
                    }
                    
                    $imageData = base64_decode($imageData);
                    if ($imageData === false) {
                        throw new \Exception('Base64 decode failed');
                    }
                    
                    $fileName = 'face_' . time() . '_' . uniqid() . '.' . $type;
                    $path = 'visitor-faces/' . $fileName;
                    
                    // Save the file to storage
                    \Storage::disk('public')->put($path, $imageData);
                    
                    $visitor->face_image = $path;
                }
            }
            
            // Save the visitor
            $visitor->save();
            
            // Store visitor ID in session
            session(['current_visitor_id' => $visitor->id]);
            
            // Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('visitor-documents', 'public');
                    $visitor->documents()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
            
            // Auto-redirect to public visit form after visitor creation
            if ($branch) {
                $route = 'public.visitor.visit.form.branch';
                $routeParams = ['company' => $company->id, 'branch' => $branch, 'visitor' => $visitor->id];
            } else {
                $route = 'public.visitor.visit.form';
                $routeParams = ['company' => $company->id, 'visitor' => $visitor->id];
            }
            
            return redirect()
                ->route($route, $routeParams)
                ->with('success', 'Visitor registered successfully! Please complete the visit form.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error registering visitor: ' . $e->getMessage());
        }
    }

public function storeVisit(Request $request, Company $company, $branch = null)
{
    $validated = $request->validate([
        'visitor_id' => 'required|exists:visitors,id',
        'department_id' => 'required|exists:departments,id',
        'person_to_visit' => 'required|string|max:255',
        'purpose' => 'required|string',
        'vehicle_number' => 'nullable|string|max:50',
        'documents' => 'nullable|array',
        'documents.*' => 'file|max:2048',
    ]);

    // Find the visitor
    $visitor = Visitor::findOrFail($request->visitor_id);
    
    // Update visitor details
    $visitor->update([
        'department_id' => $validated['department_id'],
        'person_to_visit' => $validated['person_to_visit'],
        'purpose' => $validated['purpose'],
        'vehicle_number' => $validated['vehicle_number'] ?? null,
        'status' => 'Approved',
        'approved_by' => null, // Public form, so no admin approval
        'approved_at' => now(),
        'visit_completed_at' => now(),
    ]);

    // Handle document uploads if any
    if ($request->hasFile('documents')) {
        $documents = [];
        foreach ($request->file('documents') as $file) {
            $path = $file->store('visitor_documents', 'public');
            $documents[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $file->getClientMimeType()
            ];
        }
        $visitor->documents = $documents;
        $visitor->save();
    }

    // Redirect to entry page to show updated status
    return redirect()->route('visitors.entry.page')->with('success', 'Visit details submitted successfully! You can now mark in/out.');
}

/**
 * Show the public visit form (no auth required)
 */
public function showPublicVisitForm(Company $company, $branch = null, Request $request = null)
{
    $request = $request ?? request(); // Get the request instance if not injected
    
    $branchModel = null;
    if ($branch) {
        $branchModel = $company->branches()->find($branch);
    }
    
    // Get visitor ID from query parameter or route parameter
    $visitorId = $request->query('visitor') ?? $request->route('visitor');
    
    if (!$visitorId) {
        return redirect()->back()->with('error', 'No visitor specified.');
    }
    
    $visitor = Visitor::find($visitorId);
    
    if (!$visitor) {
        return redirect()->back()->with('error', 'Visitor not found.');
    }
    
    // Store visitor ID in session
    session(['current_visitor_id' => $visitor->id]);
    
    // Get departments and visitor categories
    $departments = $company->departments()->get();
    
    // Get visitor categories for the company and branch
    $visitorCategories = \App\Models\VisitorCategory::query()
        ->when($branchModel, fn($q) => $q->where('branch_id', $branchModel->id))
        ->when(!$branchModel, fn($q) => $q->where('company_id', $company->id)->whereNull('branch_id'))
        ->orderBy('name')
        ->get();
    
    // Get employees for the branch
    $employees = \App\Models\Employee::query()
        ->when($branchModel, fn($q) => $q->where('branch_id', $branchModel->id))
        ->orderBy('name')
        ->get(['id', 'name', 'designation']);
    
    // If a specific branch QR was scanned, only show that branch
    // Otherwise show all branches of the company
    if ($branchModel) {
        $branches = collect([$branchModel]); // Only the scanned branch
    } else {
        $branches = $company->branches()->get(); // All branches
    }
    
    return view('visitors.public-visit', [
        'company' => $company,
        'branch' => $branchModel,
        'departments' => $departments,
        'visitorCategories' => $visitorCategories,
        'employees' => $employees,
        'branches' => $branches,
        'visitor' => $visitor
    ]);
}
        
        public function storePublicVisit(Request $request, Company $company, $branch = null, $visitorId = null)
        {
            // Get visitor ID from route parameter
            $visitorId = $visitorId ?? $request->route('visitor');
            
            // Debug: Log the incoming data
            \Log::info('Public visit submission attempt:', [
                'visitorId' => $visitorId,
                'companyId' => $company->id,
                'branchId' => $branch,
                'routeName' => $request->route()->getName(),
                'allRouteParams' => $request->route()->parameters()
            ]);
            
            // Find the visitor
            try {
                $visitor = Visitor::findOrFail($visitorId);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                \Log::error('Visitor not found:', ['visitorId' => $visitorId, 'error' => $e->getMessage()]);
                abort(404, 'Visitor not found.');
            }
            
            // Verify visitor belongs to company
            if ($visitor->company_id != $company->id) {
                \Log::error('Visitor company mismatch:', [
                    'visitor_id' => $visitor->id,
                    'visitor_company_id' => $visitor->company_id,
                    'route_company_id' => $company->id
                ]);
                
                // Return to QR scan page with error message
                return redirect()->route('qr.scan', ['company' => $company->id])
                    ->with('error', 'This visitor belongs to a different company. Please scan the correct QR code for company: ' . $visitor->company->name);
            }
            
            // Validate the request
            $validated = $request->validate([
                'branch_id' => 'nullable|exists:branches,id',
                'department_id' => 'required|exists:departments,id',
                'visitor_category_id' => 'nullable|exists:visitor_categories,id',
                'person_to_visit' => 'required|string|max:255',
                'purpose' => 'required|string',
                'visitor_company' => 'nullable|string|max:255',
                'visitor_website' => 'nullable|string|max:255',
                'vehicle_type' => 'nullable|string|max:20',
                'vehicle_number' => 'nullable|string|max:50',
                'goods_in_car' => 'nullable|string|max:255',
                'workman_policy' => 'nullable|in:Yes,No',
                'workman_policy_photo' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,bmp,tiff,webp|max:5120',
            ]);

            // Handle file upload
            if ($request->hasFile('workman_policy_photo')) {
                $path = $request->file('workman_policy_photo')->store('wpc_photos', 'public');
                $validated['workman_policy_photo'] = $path;
            }

            // Handle manual person_to_visit input if provided
            if ($request->filled('person_to_visit_manual')) {
                $validated['person_to_visit'] = $request->input('person_to_visit_manual');
            }
            
            // Mark visit as completed and update all data in one operation
            $validated['visit_completed_at'] = now();
            
            // Only auto-approve if company has auto-approval enabled
            if ($company->auto_approve_visitors) {
                $validated['status'] = 'Approved';
                $validated['approved_at'] = now();
            }
            
            // Update visitor with all validated data
            $visitor->update($validated);

            // Send notification if enabled
            $notificationService = new GoogleNotificationService();
            $notificationService->sendVisitFormNotification($company, $visitor, true);

            // Redirect to public-index page with success message
            if ($branch) {
                return redirect()->route('public.visitor.show', [
                    'company' => $company->id, 
                    'branch' => $branch, 
                    'visitor' => $visitor->id
                ])->with('success', 'Visit details submitted successfully!');
            } else {
                return redirect()->route('public.visitor.show', [
                    'company' => $company->id, 
                    'visitor' => $visitor->id
                ])->with('success', 'Visit details submitted successfully!');
            }
        }
        /**
         * Show the form for editing a visitor's details (public interface)
         *
         * @param  \App\Models\Company  $company
         * @param  int  $visitor  Visitor ID
         * @param  int  $branch  Branch ID (optional)
         * @return \Illuminate\View\View
         */
        public function editPublicVisit(Company $company, $visitor, $branch = null)
        {
            // Find visitor if not already an object
            if (!is_object($visitor)) {
                $visitor = Visitor::findOrFail($visitor);
            }
            
            // Get branch model if branch ID is provided
            $branchModel = null;
            if ($branch) {
                $branchModel = $company->branches()->find($branch);
            }
            
            // Get necessary data for the form
            $departments = $company->departments()->get();
            
            // Get visitor categories for company and branch
            $visitorCategories = \App\Models\VisitorCategory::query()
                ->where('company_id', $company->id)
                ->when($branchModel, fn($q) => $q->where('branch_id', $branchModel->id))
                ->orderBy('name')
                ->get();
            
            // Get employees for the branch
            $employees = \App\Models\Employee::query()
                ->when($branchModel, fn($q) => $q->where('branch_id', $branchModel->id))
                ->orderBy('name')
                ->get(['id', 'name', 'designation']);
            
            // If a specific branch is provided, only show that branch
            // Otherwise show all branches of the company
            if ($branchModel) {
                $branches = collect([$branchModel]); // Only the specified branch
            } else {
                $branches = $company->branches()->get(); // All branches
            }
            
            return view('visitors.public-visit', [
                'company' => $company,
                'branch' => $branchModel,
                'visitor' => $visitor,
                'departments' => $departments,
                'branches' => $branches,
                'visitorCategories' => $visitorCategories,
                'employees' => $employees,
            ]);
        }

        /**
         * Track visitor status (public interface)
         *
         * @param  int  $visitorId  Visitor ID
         * @return \Illuminate\View\View
         */
        public function trackVisitor($visitorId)
        {
            // Find the visitor with related data
            $visitor = Visitor::with(['company', 'department', 'branch', 'securityChecks'])
                ->findOrFail($visitorId);
            
            return view('visitors.public-track', [
                'visitor' => $visitor
            ]);
        }
}