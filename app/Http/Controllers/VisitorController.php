<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Company;
use App\Models\User;
use App\Models\Department;
use App\Models\VisitorCategory;
use App\Models\SecurityCheck;
use App\Models\Branch;
use App\Models\Employee;
use App\Services\GoogleNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Notifications\VisitorCreated;
use App\Exports\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\VisitorNotificationMail;
use App\Models\CompanyUser;


class VisitorController extends Controller
{
    
    private const NAME_REGEX = '/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'\-\.]+$/u';
    private const PHONE_REGEX = '/^\+?[0-9]{7,15}$/';

    /* --------------------------- Helpers --------------------------- */

    /**
     * Validate if visitor has completed required security check based on company settings
     */
    private function validateSecurityCheck($visitor, $action)
    {
        if (!$visitor->company || !$visitor->company->security_check_service) {
            return null; // No security check required if service is disabled
        }

        $securityType = $visitor->company->security_checkin_type;
        $hasCheckInSecurityCheck = $visitor->securityChecks()->where('check_type', 'checkin')->exists();
        $hasCheckOutSecurityCheck = $visitor->securityChecks()->where('check_type', 'checkout')->exists();
        
        // If no security check type is set or type is 'none', no validation needed
        if (empty($securityType) || $securityType === 'none') {
            return null;
        }

        // Check requirements based on action and security type
        if ($action === 'checking in') {
            // For check-in, require security check if type is 'checkin' or 'both'
            if (in_array($securityType, ['checkin', 'both']) && !$hasCheckInSecurityCheck) {
                return "Security check must be completed before checking in.";
            }
        } elseif ($action === 'checking out') {
            // For check-out, require security check if type is 'checkout' or 'both'
            if (in_array($securityType, ['checkout', 'both']) && !$hasCheckOutSecurityCheck) {
                return "Security check must be completed before checking out.";
            }
        }

        return null; // No security check required
    }

    /**
     * Undo security check-out
     */
    public function undoSecurityCheckout($id)
    {
        try {
            $securityCheck = \App\Models\SecurityCheck::findOrFail($id);
            
            // Only allow undo if it's a checkout and within 30 minutes
            if ($securityCheck->check_type !== 'checkout') {
                return response()->json(['error' => 'Only security check-outs can be undone.'], 400);
            }
            
            if (\Carbon\Carbon::parse($securityCheck->created_at)->diffInMinutes(now()) > 30) {
                return response()->json(['error' => 'Undo is only available within 30 minutes of security check-out.'], 400);
            }
            
            // Delete the security check
            $securityCheck->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Security check-out has been undone successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error undoing security check-out: ' . $e->getMessage()], 500);
        }
    }

    private function isSuper(): bool
    {
        $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        return (($u->role ?? null) === 'superadmin');
    }

    private function isCompany(): bool
    {
        $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        return (($u->role ?? null) === 'company');
    }

    // Scope queries to company for non-super admins
    private function companyScope($query)
    {
        $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        // If not superadmin, filter by company and branch
        if (!$this->isSuper()) {
            $query->where('company_id', $u->company_id);
            if (!empty($u->branch_id)) {
                $query->where('branch_id', $u->branch_id);
            }
        }
        
        // For superadmin, don't apply any filters - show all data
        return $query;
    }

    // Map base route names to company.* when inside /company/* or company role
    private function panelRoute(string $name): string
    {
        $inCompanyUrl = request()->is('company/*');
        if ($inCompanyUrl || $this->isCompany()) {
            $map = [
                'dashboard'           => 'company.dashboard',
                'visitors.index'      => 'company.visitors.index',
                'visitors.create'     => 'company.visitors.create',
                'visitors.edit'       => 'company.visitors.edit',
                'visitors.update'     => 'company.visitors.update',
                'visitors.store'      => 'company.visitors.store',
                'visitors.destroy'    => 'company.visitors.destroy',
                'visitors.history'    => 'company.visitors.history',
                'visitors.entry.page' => 'company.visitors.entry.page',
                'visitors.report'     => 'company.visitors.report',
            ];
            if (isset($map[$name])) return $map[$name];
        }
        return $name;
    }

    // Date range helper (expects ?from=YYYY-MM-DD&to=YYYY-MM-DD)
    private function applyDateRange($query, string $column, Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');

        if (!$from && !$to) return $query;

        $start = $from ? Carbon::parse($from)->startOfDay() : null;
        $end   = $to   ? Carbon::parse($to)->endOfDay()     : null;

        return $query
            ->when($start, fn($q) => $q->where($column, '>=', $start))
            ->when($end,   fn($q) => $q->where($column, '<=', $end));
    }

   private function getCompanies()
    {
        if ($this->isSuper()) {
            return Company::orderBy('name')->get();
        }
        $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        return Company::where('id', $u->company_id)->get();
    }

    private function getDepartments($branchId = null)
    {
        if ($branchId) {
            return Department::where('branch_id', $branchId)->orderBy('name')->get();
        }

        if ($this->isSuper()) {
            return Department::orderBy('name')->get();
        }

        $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        return Department::where('company_id', $u->company_id)->orderBy('name')->get();
    }


    private function authorizeVisitor($visitor)
    {
        $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        if (!$this->isSuper() && $visitor->company_id != $u->company_id) {
            abort(403, 'Unauthorized access.');
        }
    }

    /* --------------------------- CRUD --------------------------- */

    public function index()
    {
        $query = $this->companyScope(Visitor::with(['company', 'branch', 'department'])->latest());
        // Show all statuses in company list so nothing is hidden from operators
        // (Dashboard will still hide Pending/Rejected when auto-approve is on)
        $visitors = $query->paginate(10);
        return view('visitors.index', compact('visitors'));
    }

    public function visitsIndex(Request $request)
    {
        $query = $this->companyScope(Visitor::with(['company', 'branch', 'department', 'category'])->latest());
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('person_to_visit', 'like', "%{$search}%");
            });
        }
        
        // Apply filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        if ($request->filled('branch_id')) {
            $branchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
            $query->whereIn('branch_id', $branchIds);
        }
        
        if ($request->filled('department_id')) {
            $departmentIds = is_array($request->department_id) ? $request->department_id : [$request->department_id];
            $query->whereIn('department_id', $departmentIds);
        }
        
        $visitors = $query->paginate(10);
        
        // Get filter data
        $companies = $this->isSuper() ? Company::pluck('name', 'id') : [];
        
        $branches = [];
        $departments = [];
        if ($request->filled('company_id')) {
            $branches = Branch::where('company_id', $request->company_id)->pluck('name', 'id');
            $departments = Department::where('company_id', $request->company_id)->pluck('name', 'id');
        }
        
        return view('visits.index', compact('visitors', 'companies', 'branches', 'departments'));
    }

    public function create()
    {
        // Check if current time is within branch operation hours for selected branch
        if (request()->filled('branch_id') && !session('alert')) {
            $branchId = request()->input('branch_id');
            $branchModel = \App\Models\Branch::find($branchId);
            
            if ($branchModel) {
                $currentTime = now()->format('H:i');
                $startTime = date('H:i', strtotime($branchModel->start_time));
                $endTime = date('H:i', strtotime($branchModel->end_time));
                
                if ($startTime && $endTime) {
                    if ($currentTime < $startTime || $currentTime > $endTime) {
                        $errorMessage = "Visitor cannot be added before or after operational time. Branch operating hours are {$startTime} to {$endTime}.";
                        
                        // Set alert in session and redirect back
                        return redirect()->route('visitors.index')
                            ->with('error', $errorMessage)
                            ->with('alert', $errorMessage);
                    }
                }
            }
        }
        
        $companies   = $this->getCompanies();
        $departments = $this->getDepartments();
        $categories  = VisitorCategory::query()
            ->when(!$this->isSuper(), fn($q) => $q->where('company_id', auth()->user()->company_id))
            ->orderBy('name')
            ->get();
        
        // Get branches for company users
        $branches = collect();
        if (!$this->isSuper()) {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $branches = \App\Models\Branch::where('company_id', $u->company_id)
                ->orderBy('name')
                ->pluck('name', 'id');
        }

        return view('visitors.create', compact('companies', 'departments', 'categories', 'branches'));
    }
    
    public function store(Request $request)
    {
        // Check if current time is within branch operation hours for selected branch
        if ($request->filled('branch_id')) {
            $branchId = $request->input('branch_id');
            $branchModel = \App\Models\Branch::find($branchId);
            
            if ($branchModel) {
                $currentTime = now()->format('H:i');
                $startTime = date('H:i', strtotime($branchModel->start_time));
                $endTime = date('H:i', strtotime($branchModel->end_time));
                
                if ($startTime && $endTime) {
                    if ($currentTime < $startTime || $currentTime > $endTime) {
                        $errorMessage = "Visitor cannot be added before or after operational time. Branch operating hours are {$startTime} to {$endTime}.";
                        
                        return redirect()->route('visitors.index')
                            ->with('error', $errorMessage)
                            ->with('alert', $errorMessage)
                            ->withInput();
                    }
                }
            }
        }
        
        return DB::transaction(function () use ($request) {
            // ---------------------------
            // Validation rules
            // ---------------------------
            $messages = [
                'name.regex'  => 'Name may only contain letters, spaces, apostrophes, periods, and hyphens.',
                'phone.regex' => 'Phone must be digits only and can include an optional leading + (7-15 digits).',
                'face_encoding.json' => 'Invalid face data format. Please try capturing your face again.',
            ];

            $validated = $request->validate([
                'company_id'          => 'nullable|exists:companies,id',
                'branch_id'           => 'nullable|exists:branches,id',
                'name'                => 'required|string|max:255|regex:' . self::NAME_REGEX,
                'visitor_category_id' => 'nullable|exists:visitor_categories,id',
                'email'               => 'nullable|email',
                'phone'               => 'required|regex:' . self::PHONE_REGEX,
                'photo'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'face_image'          => 'nullable|string', // base64 webcam photo
                'face_encoding'       => 'nullable|string',   // Face descriptor JSON
                'department_id'       => 'nullable|exists:departments,id',
                'purpose'             => 'nullable|string|max:255',
                'person_to_visit'     => 'nullable|string|max:255',
                'visit_date'          => 'nullable|date',
                'documents'           => 'nullable|array',
                'documents.*'         => 'file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
                'visitor_company'     => 'nullable|string|max:255',
                'visitor_website'     => 'nullable|string|max:255',
                'vehicle_type'        => 'nullable|string|max:20',
                'vehicle_number'      => 'nullable|string|max:50',
                'goods_in_car'        => 'nullable|string|max:255',
                'workman_policy'      => 'nullable|in:Yes,No',
                'workman_policy_photo'=> 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,bmp,tiff,webp|max:5120',
                'document'            => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
            ], $messages);

            $validated['name'] = strip_tags(Str::squish($validated['name']));
            if (!empty($validated['email'])) {
                $validated['email'] = strtolower(strip_tags($validated['email']));
            }
            if (!empty($validated['purpose'])) {
                $validated['purpose'] = strip_tags($validated['purpose']);
            }
            if (!empty($validated['person_to_visit'])) {
                $validated['person_to_visit'] = strip_tags($validated['person_to_visit']);
            }

            // ---------------------------
            // Force company for non-superadmin
            // ---------------------------
            if (!$this->isSuper()) {
                $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
                $validated['company_id'] = $u->company_id;
                if (!empty($u->branch_id)) {
                    $validated['branch_id'] = $u->branch_id;
                }
            }

            // ---------------------------
            // Check face recognition requirement
            // ---------------------------
            if (!empty($validated['company_id'])) {
                $company = \App\Models\Company::find($validated['company_id']);
                \Log::info('Face recognition check', [
                    'company_id' => $validated['company_id'],
                    'company_name' => $company ? $company->name : 'not found',
                    'face_recognition_enabled' => $company ? $company->face_recognition_enabled : 'null',
                    'has_face_image' => $request->filled('face_image'),
                    'has_face_encoding' => $request->filled('face_encoding')
                ]);
                
                if ($company && $company->face_recognition_enabled) {
                    if (!$request->filled('face_image') || !$request->filled('face_encoding')) {
                        // Add validation error instead of throwing exception
                        $validator = validator([], []);
                        $validator->errors()->add('face_image', 'Face recognition is required for this company. Please use the camera to capture the visitor\'s face photo before submitting.');
                        throw new \Illuminate\Validation\ValidationException($validator);
                    }
                }
            }

            // Handle photo / face image
            if ($request->filled('face_image')) {
                $dataUrl = $request->input('face_image');
                if (preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $dataUrl, $m)) {
                    $ext = $m[1] === 'jpeg' ? 'jpg' : $m[1];
                    $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
                    $imageData = base64_decode($data);
                    
                    if ($imageData === false) {
                        throw new \Exception('Invalid base64 image data');
                    }
                    
                    $filename = 'visitor_faces/visitor_face_' . time() . '_' . uniqid() . '.' . $ext;
                    Storage::disk('public')->makeDirectory('visitor_faces');
                    Storage::disk('public')->put($filename, $imageData);
                    $validated['face_image'] = $filename;
                    
                    if (empty($validated['photo'])) {
                        $validated['photo'] = $filename;
                    }
                }
            } elseif ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('visitor_photos', 'public');
            }

            // Process face encoding
            if ($request->has('face_encoding') && !empty($request->face_encoding)) {
                $validated['face_encoding'] = $request->face_encoding;
            }

            // Handle document upload
            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('visitor_documents', 'public');
                $validated['documents'] = $documentPath;
            }

            // Workman policy photo
            if ($request->hasFile('workman_policy_photo')) {
                $validated['workman_policy_photo'] = $request->file('workman_policy_photo')
                    ->store('wpc_photos', 'public');
            }

            // Auto-approval logic - but keep status as Pending until visit form is completed
            $status = 'Pending';
            $approvedAt = null;

            // Don't auto-approve until visit form is completed
            // if (!empty($validated['company_id'])) {
            //     $company = Company::find($validated['company_id']);
            //     if ($company && (int) $company->auto_approve_visitors === 1) {
            //         $status = 'Approved';
            //         $approvedAt = now();
            //     }
            // }

            $validated['status'] = $status;
            if (\Schema::hasColumn('visitors', 'approved_at')) {
                $validated['approved_at'] = $approvedAt;
            }

            // Create visitor
            $visitor = Visitor::create($validated);

            // Send notification for visitor created
            try {
                if (!empty($visitor->company_id)) {
                    // Send Google notification if enabled
                    $notificationService = new GoogleNotificationService();
                    $notificationService->sendNotification(
                        $visitor->company,
                        'visitor_created',
                        "New visitor {$visitor->name} has been created",
                        $visitor
                    );
                    
                    $recipients = User::query()
                        ->where('company_id', $visitor->company_id)
                        ->when(!empty($visitor->branch_id), function ($q) use ($visitor) {
                            $q->where(function ($qq) use ($visitor) {
                                $qq->whereNull('branch_id')->orWhere('branch_id', $visitor->branch_id);
                            });
                        })
                        ->get();
                    foreach ($recipients as $user) {
                        $user->notify(new VisitorCreated($visitor));
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning('VisitorCreated notify failed: '.$e->getMessage());
            }

            // Document is already stored in documents column, no need for separate table
            // The document path is already saved in the main visitor record

            // Email notifications
            try {
                if (!empty($visitor->email)) {
                    \App\Jobs\SendVisitorEmail::dispatchSync(new \App\Mail\VisitorCreatedMail($visitor), $visitor->email);
                    if ($visitor->status === 'Approved') {
                        \App\Jobs\SendVisitorEmail::dispatchSync(new \App\Mail\VisitorApprovedMail($visitor), $visitor->email);
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning('VisitorCreated mail dispatch failed: '.$e->getMessage());
            }

            // Notify company users
            try {
                if (!empty($visitor->company_id)) {
                    $recipients = User::query()
                        ->where('company_id', $visitor->company_id)
                        ->when(!empty($visitor->branch_id), function ($q) use ($visitor) {
                            $q->where(function ($qq) use ($visitor) {
                                $qq->whereNull('branch_id')->orWhere('branch_id', $visitor->branch_id);
                            });
                        })
                        ->get();
                    foreach ($recipients as $user) {
                        $user->notify(new VisitorCreated($visitor));
                    }
                    
                    // Send emails to company users based on branch assignment
                    $companyUsers = CompanyUser::where('company_id', $visitor->company_id)->get();
                        
                    foreach ($companyUsers as $companyUser) {
                        // Check if company user should receive notification for this branch
                        $shouldNotify = true;
                        
                        // If visitor has a specific branch and company user has branch_id set
                        if (!empty($visitor->branch_id) && !empty($companyUser->branch_id)) {
                            // Only notify if company user is assigned to the same branch
                            $shouldNotify = $companyUser->branch_id == $visitor->branch_id;
                        }
                        
                        if ($shouldNotify) {
                            try {
                                Mail::to($companyUser->email)->send(new VisitorNotificationMail($visitor, $companyUser, 'created'));
                            } catch (\Exception $e) {
                                \Log::warning('Failed to send visitor notification email to company user: ' . $e->getMessage());
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning('VisitorCreated notify failed: '.$e->getMessage());
            }

            // Always redirect to visit form after visitor creation
            $route = $this->isSuper() ? 'visitors.visit.form' : 'company.visitors.visit.form';
            $message = 'Visitor registered successfully. Please complete the visit form.';

            return redirect()->route($route, $visitor->id)->with('success', $message)
                ->with('play_notification', true)
                ->with('visitor_name', $visitor->name);
        });
    }

    /**
     * Tiny helper to avoid errors if your table doesn’t have approved_at.
     * You can place this at the bottom of the controller or a base controller.
     */
    // Inside the VisitorController
    // Inside the VisitorController
    private function schema_has_column(string $table, string $column): bool
    {
        static $cache = [];
        $key = $table.':'.$column;
        if (!array_key_exists($key, $cache)) {
            try {
                $cache[$key] = \Schema::hasColumn($table, $column);  // Using the Schema facade
            } catch (\Throwable $e) {
                $cache[$key] = false;
            }
        }
        return $cache[$key];
    }

    public function edit(Visitor $visitor)
    {
        $this->authorizeVisitor($visitor);

        $companies = $this->getCompanies();
        $departments = $this->getDepartments();
        $branches = Branch::query()
            ->when(!$this->isSuper(), function($q){
                $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
                $q->where('company_id', $u->company_id);
            })
            ->orderBy('name')
            ->get();
        $categories = VisitorCategory::query()
            ->when(!$this->isSuper(), fn($q) => $q->where('company_id', auth()->user()->company_id))
            ->orderBy('name')
            ->get();

        return view('visitors.edit', compact('visitor', 'companies', 'departments', 'branches', 'categories'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $this->authorizeVisitor($visitor);

        if ($request->input('action') === 'undo') {
            if (!$visitor->status_changed_at || !in_array($visitor->status, ['Approved', 'Rejected'], true)) {
                $message = 'Undo unavailable for this visitor.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }

            if ($visitor->status_changed_at->lt(now()->subMinutes(30))) {
                $message = 'Undo window expired (30 minutes).';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }

            // Prevent undo if security check-in has occurred or visitor is checked in
            if ($visitor->security_checkin_time || $visitor->in_time) {
                $message = 'Cannot undo status after security check-in or visitor check-in.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }

            $currentStatus = $visitor->status;
            $visitor->status = 'Pending';
            $visitor->last_status = $currentStatus;
            $visitor->status_changed_at = now();
            $visitor->save();

            $message = 'Visitor status reverted to Pending';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'status'  => $visitor->status,
                    'message' => $message,
                ]);
            }

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('visitor_photos', 'public');
                $visitor->photo_path = $path;
            }

            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('visitor_documents', 'public');
                $visitor->documents = $documentPath;
            }

            return redirect()->back()->with('success', $message);
        }
        $nonBusiness = ['_token','_method'];
        $payloadCount = count($request->except($nonBusiness));
        $isAjax = $request->ajax() || $request->wantsJson();
        if ($request->has('status') && ($payloadCount === 1 || $isAjax)) {
            $request->validate([
                'status' => 'required|in:Pending,Approved,Rejected,Completed',
            ]);

            $previousStatus = $visitor->status;
            $newStatus = $request->input('status');
            
            // Update visitor status and track changes
            $visitor->last_status = $previousStatus;
            $visitor->status_changed_at = now();
            $visitor->status = $newStatus;
            
            // Set approved_by and approved_at when status changes to Approved
            if ($newStatus === 'Approved') {
                $visitor->approved_by = auth()->id();
                $visitor->approved_at = now();
            } 
            // Clear approval data when status changes from Approved
            elseif ($previousStatus === 'Approved') {
                $visitor->approved_by = null;
                $visitor->approved_at = null;
            }
            
            $visitor->save();

            // If transitioned to Approved, send mail to visitor
            if ($previousStatus !== 'Approved' && $newStatus === 'Approved' && !empty($visitor->email)) {
                try {
                    \App\Jobs\SendVisitorEmail::dispatch(new \App\Mail\VisitorApprovedMail($visitor), $visitor->email);
                } catch (\Throwable $e) {
                    \Log::error('Failed to dispatch approval email: ' . $e->getMessage());
                }
            }
            
            // Send Google notification if enabled
            if ($previousStatus !== 'Approved' && $newStatus === 'Approved') {
                try {
                    $notificationService = new GoogleNotificationService();
                    $notificationService->sendApprovalNotification($visitor->company, $visitor);
                } catch (\Throwable $e) {
                    \Log::error('Failed to send approval notification: ' . $e->getMessage());
                }
            }
            
            // Send approval notification emails to company users
            if ($previousStatus !== 'Approved' && $newStatus === 'Approved') {
                try {
                    $companyUsers = CompanyUser::where('company_id', $visitor->company_id)->get();
                        
                    foreach ($companyUsers as $companyUser) {
                        // Check if company user should receive notification for this branch
                        $shouldNotify = true;
                        
                        // If visitor has a specific branch and company user has branch_id set
                        if (!empty($visitor->branch_id) && !empty($companyUser->branch_id)) {
                            // Only notify if company user is assigned to the same branch
                            $shouldNotify = $companyUser->branch_id == $visitor->branch_id;
                        }
                        
                        if ($shouldNotify) {
                            try {
                                Mail::to($companyUser->email)->send(new VisitorNotificationMail($visitor, $companyUser, 'approved'));
                            } catch (\Exception $e) {
                                \Log::warning('Failed to send visitor approval email to company user: ' . $e->getMessage());
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to send visitor approval notifications: ' . $e->getMessage());
                }
            }

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'status'  => $visitor->status,
                    'message' => "Visitor status updated to {$visitor->status}",
                    'play_notification' => $newStatus === 'Approved'
                ]);
            }

            return redirect()->back()->with('success', "Visitor status updated to {$visitor->status}");
        }

        // Otherwise, normal full update
        $messages = [
            'name.regex'  => 'Name may only contain letters, spaces, apostrophes, periods, and hyphens.',
            'phone.regex' => 'Phone must be digits only and can include an optional leading + (7-15 digits).',
        ];

        $validated = $request->validate([
            'company_id'          => 'required|exists:companies,id',
            'name'                => 'required|string|max:255|regex:'.self::NAME_REGEX,
            'visitor_category_id' => 'nullable|exists:visitor_categories,id',
            'email'               => 'nullable|email:rfc,dns',
            'phone'               => 'required|regex:'.self::PHONE_REGEX,
            'photo'               => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'department_id'       => 'nullable|exists:departments,id',
            'purpose'             => 'nullable|string|max:255',
            'person_to_visit'     => 'nullable|string|max:255',
            'visit_date'          => 'nullable|date',
            'in_time'             => 'nullable|date',
            'out_time'            => 'nullable|date',
            'status'              => 'required|in:Pending,Approved,Rejected,Completed',
            'documents'           => 'nullable|array',
            'documents.*'         => 'file|max:5120',
            'visitor_company'     => 'nullable|string|max:255',
            'visitor_website'     => 'nullable|string|max:255',
            'vehicle_type'        => 'nullable|string|max:20',
            'vehicle_number'      => 'nullable|string|max:50',
            'goods_in_car'        => 'nullable|string|max:255',
            'workman_policy'      => 'nullable|in:Yes,No',
            'workman_policy_photo'=> 'nullable|image|max:2048',
        ], $messages);

        $validated['name'] = Str::squish($validated['name']);
        if (!empty($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        if (!$this->isSuper()) {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $validated['company_id'] = $u->company_id;
            if (!empty($u->branch_id)) {
                $validated['branch_id'] = $u->branch_id;
            }
        }

        // Photo: prefer base64 camera capture over file upload
        if ($request->filled('photo_base64')) {
            $dataUrl = $request->input('photo_base64');
            if (preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $dataUrl, $m)) {
                $ext = $m[1] === 'jpeg' ? 'jpg' : $m[1];
                $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
                $data = base64_decode($data);
                $filename = 'photos/'.uniqid('visitor_', true).'.'.$ext;
                Storage::disk('public')->put($filename, $data);
                $validated['photo'] = $filename;
            }
        } elseif ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('documents')) {
            $paths = [];
            foreach ($request->file('documents') as $doc) {
                $paths[] = $doc->store('documents', 'public');
            }
            $validated['documents'] = $paths;
        }

        if ($request->hasFile('workman_policy_photo')) {
            $validated['workman_policy_photo'] = $request->file('workman_policy_photo')->store('wpc_photos', 'public');
        }

        $previousStatus = $visitor->status;
        $isStatusChanging = array_key_exists('status', $validated) && $validated['status'] !== $previousStatus;
        $isBeingApproved = $isStatusChanging && $validated['status'] === 'Approved';
        
        $visitor->fill($validated);
        
        if ($isStatusChanging) {
            $visitor->last_status = $previousStatus;
            $visitor->status_changed_at = now();
            
            // Handle approval specific fields
            if ($isBeingApproved) {
                $visitor->approved_by = auth()->id();
                $visitor->approved_at = now();
            } elseif ($previousStatus === 'Approved') {
                // If changing from Approved to another status, clear approval data
                $visitor->approved_by = null;
                $visitor->approved_at = null;
            }
        }
        
        $visitor->save();

        // Send approval email if status changed to Approved
        if ($isBeingApproved && !empty($visitor->email)) {
            try {
                \App\Jobs\SendVisitorEmail::dispatch(new \App\Mail\VisitorApprovedMail($visitor), $visitor->email);
            } catch (\Throwable $e) {
                \Log::error('Failed to dispatch approval email: ' . $e->getMessage());
            }
        }
        
        // Send approval notification emails to company users
        if ($isBeingApproved) {
            try {
                $companyUsers = CompanyUser::where('company_id', $visitor->company_id)->get();
                    
                foreach ($companyUsers as $companyUser) {
                    // Check if company user should receive notification for this branch
                    $shouldNotify = true;
                    
                    // If visitor has a specific branch and company user has branch_id set
                    if (!empty($visitor->branch_id) && !empty($companyUser->branch_id)) {
                        // Only notify if company user is assigned to the same branch
                        $shouldNotify = $companyUser->branch_id == $visitor->branch_id;
                    }
                    
                    if ($shouldNotify) {
                        try {
                            Mail::to($companyUser->email)->send(new VisitorNotificationMail($visitor, $companyUser, 'approved'));
                        } catch (\Exception $e) {
                            \Log::warning('Failed to send visitor approval email to company user: ' . $e->getMessage());
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to send visitor approval notifications: ' . $e->getMessage());
            }
        }

        return redirect()->route($this->panelRoute('visitors.index'))
            ->with('success', 'Visitor updated successfully!');
    }

    /* --------------------------- Other flows --------------------------- */

    public function history(Request $request)
    {
        // Get the base query
        $query = $this->companyScope(Visitor::query())
            ->with(['company', 'branch', 'department'])
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('company_id'), function($q) use ($request) {
                $q->where('company_id', $request->company_id);
            })
            ->when($request->filled('branch_id'), function($q) use ($request) {
                $branchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
                $q->whereIn('branch_id', $branchIds);
            })
            ->when($request->filled('department_id'), function($q) use ($request) {
                $departmentIds = is_array($request->department_id) ? $request->department_id : [$request->department_id];
                $q->whereIn('department_id', $departmentIds);
            });

        // Apply date range filter
        $from = $request->input('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));
        
        $query->whereBetween('in_time', [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay()
        ]);

        // Get the data
        $visitors = $query->latest()->paginate(20);

        // Get filter data
        $companies = $this->isSuper() 
            ? Company::orderBy('name')->get()
            : Company::where('id', auth()->user()->company_id)->get();

        $branches = collect();
        $departments = collect();

        if ($request->filled('company_id')) {
            $branches = Branch::where('company_id', $request->company_id)
                ->orderBy('name')
                ->get();

            $departments = Department::where('company_id', $request->company_id)
                ->orderBy('name')
                ->get();
        }

        return view('visitors.history', compact(
            'visitors',
            'companies',
            'branches',
            'departments'
        ));
    }

    public function visitForm($id)
    {
        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

        // Check if visitor has already completed the visit form (and it hasn't been undone)
        if ($visitor->visit_completed_at && ($visitor->department_id || $visitor->person_to_visit || $visitor->purpose)) {
            return redirect()->route('visitors.index')
                ->with('error', 'Visitor has already completed the visit form.');
        }

        // Allow visit form completion for Pending visitors (new flow)

        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        $isSuper = $this->isSuper();
        
        // Get companies - all for super admin, only user's company for others
        $companies = $isSuper ? $this->getCompanies() : collect([$user->company]);
        
        $companyId = $visitor->company_id ?? ($companies->first()->id ?? null);

        $branches = Branch::query()
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->get(['id', 'name']);

        $selectedBranchId = $visitor->branch_id ?? ($branches->first()->id ?? null);
        $departments = $selectedBranchId ? $this->getDepartments($selectedBranchId) : collect();

        $visitorCategories = VisitorCategory::query()
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->when($companyId && !$selectedBranchId, fn($q) => $q->where('company_id', $companyId)->whereNull('branch_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        // Get employees for the selected branch
        $employees = \App\Models\Employee::query()
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->orderBy('name')
            ->get(['id', 'name', 'designation']);

        $canUndoVisit = $visitor->visit_completed_at && 
                        Carbon::parse($visitor->visit_completed_at)->gt(now()->subMinutes(30)) &&
                        !$visitor->in_time &&
                        ($visitor->department_id || $visitor->person_to_visit || $visitor->purpose);

        return view('visitors.visit', [
            'visitor' => $visitor,
            'departments' => $departments,
            'companies' => $companies,
            'branches' => $branches,
            'visitorCategories' => $visitorCategories,
            'employees' => $employees,
            'isSuper' => $isSuper,
            'user' => $user,
            'selectedBranchId' => $selectedBranchId,
            'canUndoVisit' => $canUndoVisit,
        ]);
    }

/**
 * Submit visitor visit details
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function submitVisit(Request $request, $id)
    {
        try {
            // Log the incoming request data
            \Log::info('Submit Visit Request Data:', $request->all());
            
            $visitor = Visitor::findOrFail($id);
            $this->authorizeVisitor($visitor);

            if (!$this->isSuper()) {
                $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
                $request->merge(['company_id' => $u->company_id]);
            }

            // Log before validation
            \Log::info('Before validation:', [
                'company_id' => $request->company_id,
                'department_id' => $request->department_id,
                'person_to_visit' => $request->person_to_visit,
                'current_status' => $visitor->status
            ]);

            $validated = $request->validate([
                'company_id'          => 'required|exists:companies,id',
                'department_id'       => 'required|exists:departments,id',
                'branch_id'           => 'nullable|exists:branches,id',
                'visitor_category_id' => 'nullable|exists:visitor_categories,id',
                'person_to_visit'     => 'required|string',
                'purpose'             => 'nullable|string',
                'visitor_company'     => 'nullable|string',
                'visitor_website'     => 'nullable|string',
                'vehicle_type'        => 'nullable|string',
                'vehicle_number'      => 'nullable|string',
                'goods_in_car'        => 'nullable|string',
                'workman_policy'      => 'nullable|in:Yes,No',
                'workman_policy_photo'=> 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,bmp,tiff,webp|max:5120',
                'status'              => 'sometimes|in:Pending,Approved,Rejected',
            ]);

            // Start a database transaction
            \DB::beginTransaction();

            try {
                // Handle file upload if present
                if ($request->hasFile('workman_policy_photo')) {
                    // Delete old photo if exists
                    if ($visitor->workman_policy_photo) {
                        \Storage::disk('public')->delete($visitor->workman_policy_photo);
                    }
                    $path = $request->file('workman_policy_photo')->store('wpc_photos', 'public');
                    $visitor->workman_policy_photo = $path;
                }

                // Get all input except the ones we don't want to update
                $updateData = $request->except(['workman_policy_photo', '_token', '_method', 'status', 'person_to_visit_manual']);
                
                // Handle manual person_to_visit input if provided
                if ($request->filled('person_to_visit_manual')) {
                    $updateData['person_to_visit'] = $request->input('person_to_visit_manual');
                }
                
                // Only update status if it's explicitly provided in the request
                if ($request->has('status')) {
                    $updateData['status'] = $request->status;
                    
                    // Handle approval specific fields
                    if ($request->status === 'Approved' && $visitor->status !== 'Approved') {
                        $updateData['approved_by'] = auth()->id();
                        $updateData['approved_at'] = now();
                    } elseif ($visitor->status === 'Approved' && $request->status !== 'Approved') {
                        // If changing from Approved to another status, clear approval data
                        $updateData['approved_by'] = null;
                        $updateData['approved_at'] = null;
                    }
                }
                
                // Log data before update
                \Log::info('Updating visitor with data:', [
                    'visitor_id' => $visitor->id,
                    'current_status' => $visitor->status,
                    'new_status' => $request->status ?? 'not_changed',
                    'data' => $updateData
                ]);

                // Update the visitor
                $visitor->update($updateData);
                
                // Mark visit form as completed
                $visitor->visit_completed_at = now();
                
                // Check if company has auto-approval and apply it after visit form completion
                if (!empty($visitor->company_id)) {
                    $company = Company::find($visitor->company_id);
                    if ($company && $company->auto_approve_visitors && $visitor->status === 'Pending') {
                        $updateData['status'] = 'Approved';
                        $updateData['approved_by'] = auth()->id();
                        $updateData['approved_at'] = now();
                        $visitor->update($updateData);
                    }
                }
                
                $visitor->save();

                // Send notification if enabled
                try {
                    $notificationService = new GoogleNotificationService();
                    $notificationService->sendVisitFormNotification($visitor->company, $visitor, false);
                } catch (\Throwable $e) {
                    \Log::error('Failed to send visit form notification: ' . $e->getMessage());
                }

                // Commit the transaction
                \DB::commit();

                // Log successful update
                \Log::info('Visitor updated successfully', [
                    'visitor_id' => $visitor->id,
                    'new_status' => $visitor->fresh()->status
                ]);

                // Determine the appropriate redirect route based on user type
                if (auth()->guard('company')->check()) {
                    return redirect()->route('visits.index')
                        ->with('success', 'Visit submitted successfully.');
                } else {
                    return redirect()->route('visits.index')
                        ->with('success', 'Visit submitted successfully.');
                }

            } catch (\Exception $e) {
                \DB::rollBack();
                // Log the detailed error
                \Log::error('Error updating visitor: ' . $e->getMessage(), [
                    'visitor_id' => $visitor->id,
                    'exception' => $e->getTraceAsString(),
                    'input' => $request->except(['workman_policy_photo', '_token'])
                ]);
                
                return back()->withInput()
                    ->with('error', 'Failed to update visitor: ' . $e->getMessage());
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            \Log::error('Validation error in submitVisit:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            // Log the error with more context
            \Log::error('Unexpected error in submitVisit: ' . $e->getMessage(), [
                'visitor_id' => $id,
                'exception' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return back()->withInput()
                ->with('error', 'An error occurred while saving the visit: ' . $e->getMessage());
        }
    }

    public function undoVisit($id)
    {
        $visitor = $this->companyScope(Visitor::findOrFail($id));
        $this->authorizeVisitor($visitor);

        // Check if visit form was completed
        if (!$visitor->visit_completed_at) {
            return redirect()->back()->with('error', 'No visit form submission to undo.');
        }

        // Convert to Carbon if it's a string
        $visitCompletedAt = is_string($visitor->visit_completed_at) 
            ? \Carbon\Carbon::parse($visitor->visit_completed_at) 
            : $visitor->visit_completed_at;

        // Check if within 30-minute window
        if ($visitCompletedAt->lt(now()->subMinutes(30))) {
            return redirect()->back()->with('error', 'Undo window expired (30 minutes).');
        }

        // Prevent undo if visitor has checked in
        if ($visitor->in_time) {
            return redirect()->back()->with('error', 'Cannot undo visit form after visitor has checked in.');
        }

        // Clear visit form data
        $visitor->update([
            'visit_completed_at' => null,
            'department_id' => null,
            'visitor_category_id' => null,
            'person_to_visit' => null,
            'purpose' => null,
            'visitor_company' => null,
            'visitor_website' => null,
            'vehicle_type' => null,
            'vehicle_number' => null,
            'goods_in_car' => null,
            'workman_policy' => null,
            'workman_policy_photo' => null,
        ]);

        return redirect()->route(auth()->guard('company')->check() ? 'company.visitors.visit.form' : 'visitors.visit.form', $visitor->id)
            ->with('success', 'Visit form submission undone successfully. All visit details have been cleared.');
    }

    public function entryPage(Request $request)
    {
        $visitors = $this->companyScope(Visitor::query()->with(['company', 'branch', 'department', 'securityChecks'])
            ->whereIn('status', ['Approved', 'Completed'])
            ->when($request->filled('company_id'), function($q) use ($request) {
                $q->where('company_id', $request->company_id);
            })
            ->when($request->filled('branch_id'), function($q) use ($request) {
                $branchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
                $q->whereIn('branch_id', $branchIds);
            })
            ->when($request->filled('department_id'), function($q) use ($request) {
                $departmentIds = is_array($request->department_id) ? $request->department_id : [$request->department_id];
                $q->whereIn('department_id', $departmentIds);
            })
            ->latest('created_at'))->paginate(10);
        $isCompany = $this->isCompany();
        
        // Get companies for superadmin
        $companies = [];
        $branches = [];
        $departments = [];
        
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        if ($this->isSuper()) {
            // For superadmin, get all companies
            $companies = Company::pluck('name', 'id');
            
            // If company is selected, get its branches and departments
            if (request('company_id')) {
                $branches = Branch::where('company_id', request('company_id'))->pluck('name', 'id');
                $departments = Department::where('company_id', request('company_id'))->pluck('name', 'id');
            }
        } else if ($this->isCompany()) {
            // For company users, get their assigned branches and departments
            // Get user's assigned branch IDs from the pivot table
            $userBranchIds = $user->branches()->pluck('branches.id')->toArray();
            
            if (!empty($userBranchIds)) {
                // Filter branches by user's assigned branches
                $branches = Branch::whereIn('id', $userBranchIds)->pluck('name', 'id');
                
                // Get user's assigned department IDs from the pivot table
                $userDepartmentIds = $user->departments()->pluck('departments.id')->toArray();
                
                if (!empty($userDepartmentIds)) {
                    // Filter departments by user's assigned departments
                    $departments = Department::whereIn('id', $userDepartmentIds)
                        ->where('company_id', $user->company_id)
                        ->pluck('name', 'id');
                } else {
                    // Fallback: filter departments by user's assigned branches
                    $departments = Department::whereIn('branch_id', $userBranchIds)
                        ->where('company_id', $user->company_id)
                        ->pluck('name', 'id');
                }
            } else {
                // Fallback to single branch if user has branch_id set
                if ($user->branch_id) {
                    $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
                    $departments = Department::where('branch_id', $user->branch_id)
                        ->where('company_id', $user->company_id)
                        ->pluck('name', 'id');
                } else {
                    // If no branches assigned, get all company branches/departments
                    $branches = Branch::where('company_id', $user->company_id)->pluck('name', 'id');
                    $departments = Department::where('company_id', $user->company_id)->pluck('name', 'id');
                }
            }
            
            // If company has no branches, show empty collection
            if ($branches->isEmpty()) {
                $branches = collect();
            }
        }
        
        return view('visitors.entry', compact(
            'visitors', 
            'isCompany', 
            'companies',
            'branches',
            'departments'
        ));
    }

    /**
     * Toggle visitor check-in/out status for company users
     *
     * @param int $id Visitor ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function companyToggleEntry($id)
    {
        try {
            $visitor = Visitor::findOrFail($id);
            $this->authorizeVisitor($visitor);
            
            $isCheckingIn = !$visitor->in_time;
            
            DB::beginTransaction();
            
            if ($isCheckingIn) {
                // Check if already checked in
                if ($visitor->in_time) {
                    return redirect()->route('company.visitors.entry.page')
                        ->with('error', 'Visitor is already checked in.');
                }
                
                // Check if visitor is approved or if auto-approval is enabled
                $companyAuto = (bool) optional($visitor->company)->auto_approve_visitors;
                if (!$companyAuto && $visitor->status !== 'Approved') {
                    return redirect()->route('company.visitors.entry.page')
                        ->with('error', 'Visitor must be approved before checking in.');
                }
                
                // Only update check-in time, don't modify the status
                $visitor->in_time = now();
                
                // If auto-approval is enabled and status is Pending, update to Approved
                if ($companyAuto && $visitor->status === 'Pending') {
                    $visitor->status = 'Approved';
                    $visitor->approved_by = auth()->id();
                    $visitor->approved_at = now();
                }
                
                // Send notification if enabled
                try {
                    $notificationService = new GoogleNotificationService();
                    $notificationService->sendMarkInNotification($visitor->company, $visitor);
                } catch (\Throwable $e) {
                    \Log::error('Failed to send mark-in notification: ' . $e->getMessage());
                }
                
                $message = 'Visitor checked in successfully.';
            } else {
                // Check if already checked out
                if ($visitor->out_time) {
                    return redirect()->route('company.visitors.entry.page')
                        ->with('error', 'Visitor has already been checked out.');
                }
                
                $visitor->out_time = now();
                
                // Send notification if enabled
                try {
                    $notificationService = new GoogleNotificationService();
                    $notificationService->sendMarkOutNotification($visitor->company, $visitor);
                } catch (\Throwable $e) {
                    \Log::error('Failed to send mark-out notification: ' . $e->getMessage());
                }
                
                // Only update status to Completed if it's currently Approved
                if ($visitor->status === 'Approved') {
                    $visitor->status = 'Completed';
                }
                $message = 'Visitor checked out successfully.';
            }
            
            $visitor->save();
            DB::commit();
            
            return redirect()->route('company.visitors.entry.page')
                ->with('success', $message)
                ->with('play_notification', true);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Company Toggle Entry Error: ' . $e->getMessage(), [
                'visitor_id' => $id,
                'exception' => $e
            ]);
            
            return redirect()->route('company.visitors.entry.page')
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Toggle visitor check-in/out status
     *
     * @param int $id Visitor ID
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggleEntry($id)
    {
        // Log the request details for debugging
        \Log::info('Toggle Entry Request:', [
            'visitor_id' => $id,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ajax' => request()->ajax(),
            'input' => request()->all(),
            'user' => auth()->user() ? auth()->user()->toArray() : null,
            'company_user' => auth('company')->user() ? auth('company')->user()->toArray() : null,
            'headers' => request()->header(),
            'previous_url' => url()->previous(),
            'current_url' => url()->current()
        ]);

        // If you later add guard role, leave this check; otherwise remove.
        if ((auth()->user()->role ?? null) === 'guard') {
            if (request()->ajax()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

        // Check if this is a QR flow visitor and if the company allows mark in/out in QR flow
        $isPublicRequest = request()->has('public') || 
                          (request()->header('referer') && str_contains(request()->header('referer'), '/public/')) ||
                          (request()->query('public') == '1');
        
        if ($isPublicRequest && !$visitor->company->mark_in_out_in_qr_flow) {
            $message = 'Mark in/out is not allowed for QR flow visitors for this company.';
            if (request()->ajax()) {
                return response()->json(['error' => $message], 403);
            }
            return back()->with('error', $message);
        }

        // Check if this is a face verification request
        $isFaceVerification = request()->has('face_verification') && request()->input('face_verification') === '1';
        $skipFaceVerification = request()->has('skip_face_verification') && request()->input('skip_face_verification') === '1';
        $faceVerified = request()->input('face_verified') === '1';

        // Only require face verification if this is specifically a face verification request
        if ($isFaceVerification && $visitor->face_encoding && !$faceVerified) {
            if (request()->ajax()) {
                return response()->json([
                    'error' => 'Face verification failed or was not completed',
                    'requires_face_verification' => true
                ], 400);
            }
            return redirect()->route('visitors.entry.page')
                ->with('error', 'Face verification is required when using face verification. Please try again or use the standard check-in.');
        }

        $originalStatus = $visitor->status;
        $action = request()->input('action', (!$visitor->in_time ? 'in' : 'out'));
        
        try {
            DB::beginTransaction();

            // Handle undo actions
            if ($action === 'undo_in') {
                if (!$visitor->in_time || Carbon::parse($visitor->in_time)->diffInMinutes(now()) > 30) {
                    $message = 'Undo is only available within 30 minutes of check-in.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }                
                // Prevent undo if security check-in has occurred
                if ($visitor->security_checkin_time) {
                    $message = 'Cannot undo check-in after security check-in.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }
                
                $visitor->in_time = null;
                $visitor->status = 'Pending';
                $message = 'Check-in has been undone successfully.';
                
            } elseif ($action === 'undo_out') {
                if (!$visitor->out_time || Carbon::parse($visitor->out_time)->diffInMinutes(now()) > 30) {
                    $message = 'Undo is only available within 30 minutes of check-out.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }
                $visitor->out_time = null;
                $visitor->status = 'Approved';
                $message = 'Check-out has been undone successfully.';
                
            } elseif ($action === 'in' || !$visitor->in_time) {
                // Check if visitor has completed visit form (check both visit_completed_at and required fields)
                $hasCompletedVisitForm = $visitor->visit_completed_at || ($visitor->department_id && $visitor->purpose);
                
                if (!$hasCompletedVisitForm) {
                    $message = 'Visitor must complete visit form before checking in.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }
                
                // Check if visitor is approved first
                if ($visitor->status !== 'Approved') {
                    $message = 'Visitor must be approved before checking in.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }
                
                // Check security requirements
                $securityError = $this->validateSecurityCheck($visitor, 'checking in');
                if ($securityError) {
                    if (request()->ajax()) {
                        return response()->json(['error' => $securityError], 400);
                    }
                    return back()->with('error', $securityError);
                }
                
                // Original check-in logic
                if ($visitor->in_time) {
                    $message = 'Visitor has already been checked in.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }
                
                $visitor->in_time = now();
                $message = 'Visitor checked in successfully.';
                $playNotification = true;
                
            } elseif ($action === 'out' || ($visitor->in_time && !$visitor->out_time)) {
                // Check if visitor has completed visit form (check both visit_completed_at and required fields)
                $hasCompletedVisitForm = $visitor->visit_completed_at || ($visitor->department_id && $visitor->purpose);
                
                if (!$hasCompletedVisitForm) {
                    $message = 'Visitor must complete visit form before checking out.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }
                
                // Check if visitor is approved first
                if ($visitor->status !== 'Approved') {
                    $message = 'Visitor must be approved before checking out.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }
                
                // Check security requirements
                $securityError = $this->validateSecurityCheck($visitor, 'checking out');
                if ($securityError) {
                    if (request()->ajax()) {
                        return response()->json(['error' => $securityError], 400);
                    }
                    return back()->with('error', $securityError);
                }
                
                // Original check-out logic
                if ($visitor->out_time) {
                    $message = 'Visitor has already been checked out.';
                    if (request()->ajax()) {
                        return response()->json(['error' => $message], 400);
                    }
                    return back()->with('error', $message);
                }
                $visitor->out_time = now();
                if ($visitor->status === 'Approved') {
                    $visitor->status = 'Completed';
                }
                $message = 'Visitor checked out successfully.';
                $playNotification = true;
                $playNotification = true;
            }

            // Update status history if status changed
            if ($visitor->isDirty('status')) {
                $visitor->last_status = $originalStatus;
                $visitor->status_changed_at = now();
            }
            
            $visitor->save();
            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'play_notification' => $playNotification ?? false,
                    'visitor' => [
                        'id' => $visitor->id,
                        'in_time' => $visitor->in_time,
                        'out_time' => $visitor->out_time,
                        'status' => $visitor->status
                    ]
                ]);
            }

            // Update status history if status changed
            if ($visitor->isDirty('status')) {
                $visitor->last_status = $originalStatus;
                $visitor->status_changed_at = now();
            }
            
            $visitor->save();
            DB::commit();
            
            // Check if this is a public visitor request (AFTER all updates)
            $isPublicRequest = request()->has('public') || 
                               (request()->header('referer') && str_contains(request()->header('referer'), '/public/')) ||
                               (request()->query('public') == '1');
            
            if ($isPublicRequest) {
                // If visitor has completed their visit (checked out), clear session and show fresh public index
                if ($visitor->out_time && $visitor->status === 'Completed') {
                    // Clear the visitor from session so they see a fresh public index
                    session()->forget('current_visitor_id');
                    
                    return redirect()->route('qr.scan', ['company' => $visitor->company_id])
                        ->with('success', $message)
                        ->with('play_notification', $playNotification ?? false)
                        ->with('visit_completed', true);
                } else {
                    return redirect()->route('public.visitor.show', ['company' => $visitor->company_id, 'visitor' => $visitor->id])
                        ->with('success', $message)
                        ->with('play_notification', $playNotification ?? false);
                }
            }

            return redirect()->route('visitors.entry.page')
                ->with('success', $message)
                ->with('play_notification', $playNotification ?? false);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Toggle Entry Error: ' . $e->getMessage(), [
                'visitor_id' => $id,
                'exception' => $e
            ]);
            
            $errorMessage = 'An error occurred. Please try again.';
            if (request()->ajax()) {
                return response()->json(['error' => $errorMessage], 500);
            }
            return back()->with('error', $errorMessage);
        }
    }

    public function printPass($id)
    {
        $visitor = Visitor::with(['company', 'department', 'branch'])->findOrFail($id);
        
        // For public routes, skip authorization check
        // Only check authorization for authenticated routes
        if (auth()->check()) {
            $this->authorizeVisitor($visitor);
        }

        // Debugging company data before checking status
        if (!$visitor->company) {
            // Log if company is missing or null
            \Log::warning('Visitor does not have a company', ['visitor_id' => $visitor->id]);
        }

        if ($visitor->status !== 'Approved' && $visitor->status !== 'Completed') {
            return redirect()->back()->with('error', 'Pass not available.');
        }

        return view('visitors.pass', [
            'visitor' => $visitor,
            'company' => $visitor->company
        ]);
    }

    public function downloadPassPDF($id)
    {
        $visitor = Visitor::with(['company', 'department', 'branch'])->findOrFail($id);
        
        // For public routes, skip authorization check
        // Only check authorization for authenticated routes
        if (auth()->check()) {
            $this->authorizeVisitor($visitor);
        }

        // Debugging company data before checking status
        if (!$visitor->company) {
            \Log::warning('Visitor does not have a company', ['visitor_id' => $visitor->id]);
        }

        if ($visitor->status !== 'Approved' && $visitor->status !== 'Completed') {
            return redirect()->back()->with('error', 'Pass not available.');
        }

        // Generate PDF using the pass_pdf view
        $pdf = \PDF::loadView('visitors.pass_pdf', [
            'visitor' => $visitor,
            'company' => $visitor->company
        ]);

        // Configure PDF to maintain design
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'enable_fontsubsetting' => true,
            'font_dir' => public_path('fonts'),
        ]);

        // Download the PDF
        return $pdf->download('visitor-pass-' . $visitor->id . '-' . str_replace(' ', '-', $visitor->name) . '.pdf');
    }

    // --------------------------- AJAX: Lookup by phone ---------------------------
    public function lookupByPhone(Request $request)
    {
        $phone = trim((string)$request->query('phone'));
        if ($phone === '') {
            return response()->json(null);
        }

        $query = Visitor::query()->where('phone', $phone)->latest('created_at');
        if (!$this->isSuper()) {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $query->where('company_id', $u->company_id);
        }

        $v = $query->first();
        if (!$v) return response()->json(null);

        return response()->json([
            'id'                 => $v->id,
            'name'               => $v->name,
            'email'              => $v->email,
            'phone'              => $v->phone,
            'visitor_category_id'=> $v->visitor_category_id,
            'department_id'      => $v->department_id,
            'purpose'            => $v->purpose,
            'person_to_visit'    => $v->person_to_visit,
            'visitor_company'    => $v->visitor_company,
            'visitor_website'    => $v->visitor_website,
            'vehicle_type'       => $v->vehicle_type,
            'vehicle_number'     => $v->vehicle_number,
            'goods_in_car'       => $v->goods_in_car,
            'security_checkin_time' => $v->security_checkin_time,
            'security_checkout_time' => $v->security_checkout_time
        ]);
    }

    /* --------------------------- Reports --------------------------- */

    // Visitor Report (filters by in_time)
    /**
     * Display a listing of visitors for reporting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function report(Request $request)
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;

        // Get counts for today and this month
        $todayVisitors = $this->companyScope(
            Visitor::whereDate('created_at', $today)
        )->count();

        $monthVisitors = $this->companyScope(
            Visitor::whereMonth('created_at', $month)
        )->count();

        // Get status counts
        $statusCounts = $this->companyScope(
            Visitor::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
        )->pluck('total', 'status');

        // Start building the query
        $query = $this->companyScope(Visitor::query())
            ->with(['company', 'department', 'branch'])
            ->latest();

        // Apply date range filter
        $this->applyDateRange($query, 'in_time', $request);

        // Apply company filter if provided and user is superadmin
        if ($request->filled('company_id') && auth()->user()->role === 'superadmin') {
            $query->where('company_id', $request->company_id);
        }

        // Apply department filter if provided
        if ($request->filled('department_id')) {
            $departmentIds = is_array($request->department_id) ? $request->department_id : [$request->department_id];
            $query->whereIn('department_id', $departmentIds);
        }
        
        // Apply branch filter if provided
        if ($request->filled('branch_id')) {
            $branchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
            $query->whereIn('branch_id', $branchIds);
        }

        // Get companies for filter dropdown
        $companies = $this->getCompanies()->pluck('name', 'id');
        
        // Get departments based on selected company
        $departmentsQuery = $this->getDepartments();
        if ($request->filled('company_id')) {
            $departmentsQuery->where('company_id', $request->company_id);
        }
        $departments = $departmentsQuery->pluck('name', 'id');
        
        // Get branches based on selected company
        $branchesQuery = Branch::query();
        if (auth()->user()->role !== 'superadmin') {
            $branchesQuery->where('company_id', auth()->user()->company_id);
        } elseif ($request->filled('company_id')) {
            $branchesQuery->where('company_id', $request->company_id);
        }
        $branches = $branchesQuery->pluck('name', 'id');

        // Get paginated results
        $visitors = $query->paginate(10)->appends($request->query());

        return view('visitors.report', compact(
            'visitors', 
            'todayVisitors', 
            'monthVisitors', 
            'statusCounts',
            'companies',
            'departments',
            'branches'
        ));
    }
    public function inOutReport(Request $request)
    {
        $query = $this->companyScope(Visitor::query())->with(['company', 'department', 'branch', 'logs']);

        if ($request->filled('from') || $request->filled('to')) {
            $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : null;
            $to   = $request->input('to')   ? Carbon::parse($request->input('to'))->endOfDay()   : null;

            $query->where(function ($q) use ($from, $to) {
                $q->when($from, fn($qq) => $qq->where('in_time', '>=', $from))
                ->when($to,   fn($qq) => $qq->where('in_time', '<=', $to));
            })->orWhere(function ($q) use ($from, $to) {
                $q->when($from, fn($qq) => $qq->where('out_time', '>=', $from))
                ->when($to,   fn($qq) => $qq->where('out_time', '<=', $to));
            });
        }

        // Apply company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $departmentIds = is_array($request->department_id) ? $request->department_id : [$request->department_id];
            $query->whereIn('department_id', $departmentIds);
        }

        // Apply branch filter
        if ($request->filled('branch_id')) {
            $branchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
            $query->whereIn('branch_id', $branchIds);
        }

        $visits = $query->latest('in_time')->paginate(20);
        
        // Get filter data
        $companies = $this->getCompanies();

        $branchesQuery = Branch::query();
        if ($request->filled('company_id')) {
            $branchesQuery->where('company_id', $request->company_id);
        } elseif (!$this->isSuper()) {
            $branchesQuery->where('company_id', auth()->user()->company_id);
        }
        $branches = $branchesQuery->orderBy('name')->get(['id', 'name']);

        $departments = collect();
        if ($request->filled('branch_id')) {
            $departments = Department::where('branch_id', $request->branch_id)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('visitors.visitor_inout', compact(
            'visits', 
            'companies', 
            'departments', 
            'branches'
        ));
    }

    /**
    * Approval Status Report (filter by company, department, branch + date range)
    */
    public function approvalReport(Request $request)
    {
        $query = $this->companyScope(Visitor::query())
            ->with(['department', 'approvedBy', 'rejectedBy', 'company', 'branch'])
            ->latest('updated_at');

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            \Log::info('Approval Report - Status filter applied: ' . $request->status);
        } else {
            // Temporarily show ALL statuses to see what's in the database
            // $query->whereIn('status', ['Approved', 'Rejected']);
            \Log::info('Approval Report - Showing ALL statuses for debugging');
        }

        // Apply branch filter (handle array from dropdown checkboxes)
        if ($request->filled('branch_ids')) {
            $query->whereIn('branch_id', $request->branch_ids);
            \Log::info('Approval Report - Branch IDs filter: ' . json_encode($request->branch_ids));
        }

        // Apply department filter (handle array from dropdown checkboxes)
        if ($request->filled('department_ids')) {
            $query->whereIn('department_id', $request->department_ids);
            \Log::info('Approval Report - Department IDs filter: ' . json_encode($request->department_ids));
        }

        // Apply date range filter
        $this->applyDateRange($query, 'updated_at', $request);

        // Log the final SQL query for debugging
        \Log::info('Approval Report Query: ' . $query->toSql());
        \Log::info('Approval Report Bindings: ' . json_encode($query->getBindings()));

        // Apply company filter if provided and user is superadmin
        if ($request->filled('company_id') && auth()->user()->role === 'superadmin') {
            $query->where('company_id', $request->company_id);
        }

        // Get companies for filter dropdown
        $companies = $this->getCompanies()->pluck('name', 'id');
        
        // Get departments based on selected company
        $departmentsQuery = $this->getDepartments();
        if ($request->filled('company_id')) {
            $departmentsQuery->where('company_id', $request->company_id);
        }
        $departments = $departmentsQuery->pluck('name', 'id');
        
        // Get branches based on selected company
        $branchesQuery = Branch::query();
        if (auth()->user()->role !== 'superadmin') {
            $branchesQuery->where('company_id', auth()->user()->company_id);
        } elseif ($request->filled('company_id')) {
            $branchesQuery->where('company_id', $request->company_id);
        }
        $branches = $branchesQuery->pluck('name', 'id');

        $visitors = $query->paginate(10)->appends($request->query());

        // Log the total count for debugging
        \Log::info('Approval Report - Total visitors found: ' . $visitors->total());

        return view('visitors.approval_status', compact(
            'visitors',
            'companies',
            'departments',
            'branches'
        ));
    }

    // Security Checkpoints Report (filter by creation timestamp; acts as verification time)
    public function securityReport(Request $request)
    {
        $query = SecurityCheck::with(['visitor'])->latest('created_at');

        if (!$this->isSuper()) {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $query->whereHas('visitor', function ($v) use ($u) {
                $v->where('company_id', $u->company_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $this->applyDateRange($query, 'created_at', $request);

    $securityChecks = $query->paginate(10)->appends($request->query());
    
    // Get companies for the filter dropdown
    $companies = $this->getCompanies();
    
    // Get departments based on selected company
    $departments = [];
    if ($request->filled('company_id')) {
        $departments = Department::where('company_id', $request->company_id)
            ->pluck('name', 'id')
            ->toArray();
    }

    return view('visitors.security_checkpoints', compact('securityChecks', 'companies', 'departments'));
    }

    // Hourly visitors report (counts of in/out per hour over a date range)
    // In ReportController.php

    public function hourlyReport(Request $request)
    {
        $query = Visitor::whereNotNull('in_time')
            ->leftJoin('branches', 'visitors.branch_id', '=', 'branches.id')
            ->select(
                DB::raw('HOUR(in_time) as hour'),
                DB::raw('DATE(in_time) as date'),
                'branches.name as branch_name',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour', 'date', 'branches.name')
            ->orderBy('branches.name')
            ->orderBy('date')
            ->orderBy('hour');

        // Apply date range filter
        if ($request->filled('from')) {
            $query->whereDate('in_time', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('in_time', '<=', $request->to);
        }

        // Apply company filter
        if ($request->filled('company_id')) {
            $query->where('visitors.company_id', $request->company_id);
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Apply branch filter
        if ($request->filled('branch_id')) {
            $query->where('visitors.branch_id', $request->branch_id);
        }

        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->where('visitors.company_id', auth()->user()->company_id);

            // If user has specific departments assigned
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('department_id', auth()->user()->departments->pluck('id'));
            }
        }

        // Get the raw results
        $hourlyData = $query->get();

        // Format the data for the view
        $series = $hourlyData->map(function($item) {
            return [
                'hour' => $item->date . ' ' . str_pad($item->hour, 2, '0', STR_PAD_LEFT) . ':00:00',
                'branch_name' => $item->branch_name ?? 'Unknown Branch',
                'count' => $item->count
            ];
        })->toArray();

        // Get companies, departments, and branches for filters
        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);
        
        // Get branches based on selected company
        $branches = [];
        if ($request->filled('company_id')) {
            $branches = \App\Models\Branch::where('company_id', $request->company_id)
                ->pluck('name', 'id')
                ->toArray();
        }

        return view('visitors.reports_hourly', [
            'series' => $series,
            'from' => $request->input('from', now()->startOfDay()->format('Y-m-d')),
            'to' => $request->input('to', now()->endOfDay()->format('Y-m-d')),
            'companies' => $companies->pluck('name', 'id'),
            'departments' => $departments->pluck('name', 'id'),
            'branches' => $branches,
            'filters' => $request->all()
        ]);
    }

    public function reportExport(Request $request)
    {
        $query = $this->companyScope(
            Visitor::with(['category', 'department'])
        )->latest('in_time');

        $this->applyDateRange($query, 'in_time', $request);

        $visitors = $query->get();

        $headings = [
            'Visitor Name',
            'Visitor Category',
            'Department Visited',
            'Person Visited',
            'Purpose of Visit',
            'Vehicle (Type / No.)',
            'Goods in Vehicle',
            'Documents',
            'Workman Policy',
            'Date',
            'Entry Time',
            'Exit Time',
            'Duration',
            'Visit Frequency',
            'Comments',
        ];

        $rows = $visitors->map(function ($visitor) {
            $vehicleType = trim((string) ($visitor->vehicle_type ?? ''));
            $vehicleNumber = trim((string) ($visitor->vehicle_number ?? ''));
            $vehicleCombined = $vehicleType || $vehicleNumber
                ? trim($vehicleType . ($vehicleType && $vehicleNumber ? ' / ' : '') . $vehicleNumber)
                : '—';

            $documents = collect($visitor->documents ?? [])->map(function ($doc) {
                return basename((string) $doc);
            })->filter()->implode(', ');
            $documents = $documents !== '' ? $documents : '—';

            $workmanPolicy = $visitor->workman_policy ?? '—';
            if (!empty($visitor->workman_policy_photo)) {
                $workmanPolicy .= ' (Photo Available)';
            }

            $date = $visitor->in_time ? Carbon::parse($visitor->in_time)->format('Y-m-d') : '—';
            $inTime = $visitor->in_time ? Carbon::parse($visitor->in_time)->format('h:i A') : '—';
            $outTime = $visitor->out_time ? Carbon::parse($visitor->out_time)->format('h:i A') : '—';

            $duration = '—';
            if ($visitor->in_time && $visitor->out_time) {
                $diff = Carbon::parse($visitor->in_time)->diff(Carbon::parse($visitor->out_time));
                $duration = sprintf('%dh %dm', $diff->h, $diff->i);
            }

            return [
                $visitor->name,
                optional($visitor->category)->name ?? '—',
                optional($visitor->department)->name ?? '—',
                $visitor->person_to_visit ?? '—',
                $visitor->purpose ?? '—',
                $vehicleCombined,
                $visitor->goods_in_car ?? '—',
                $documents,
                $workmanPolicy,
                $date,
                $inTime,
                $outTime,
                $duration,
                $visitor->visits_count ?? 1,
                $visitor->comments ?? '—',
            ];
        })->toArray();

        return Excel::download(
            new ArrayExport($headings, $rows),
            'visitor-report-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function inOutReportExport(Request $request)
    {
        $query = $this->companyScope(Visitor::query());

        if ($request->filled('from') || $request->filled('to')) {
            $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : null;
            $to   = $request->input('to')   ? Carbon::parse($request->input('to'))->endOfDay()   : null;

            $query->where(function ($q) use ($from, $to) {
                $q->when($from, fn($qq) => $qq->where('in_time', '>=', $from))
                  ->when($to,   fn($qq) => $qq->where('in_time', '<=', $to));
            })->orWhere(function ($q) use ($from, $to) {
                $q->when($from, fn($qq) => $qq->where('out_time', '>=', $from))
                  ->when($to,   fn($qq) => $qq->where('out_time', '<=', $to));
            });
        }

        $visitors = $query->latest('in_time')->get();

        $headings = ['Visitor Name', 'Entry Time', 'Exit Time', 'Verification Method'];

        $rows = $visitors->map(function ($visitor) {
            $inTime = $visitor->in_time ? Carbon::parse($visitor->in_time)->format('Y-m-d h:i A') : '—';
            $outTime = $visitor->out_time ? Carbon::parse($visitor->out_time)->format('Y-m-d h:i A') : '—';

            return [
                $visitor->name,
                $inTime,
                $outTime,
                $visitor->verification_method ?? '—',
            ];
        })->toArray();

        return Excel::download(
            new ArrayExport($headings, $rows),
            'visitor-inout-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function approvalReportExport(Request $request)
    {
        $query = $this->companyScope(Visitor::query())
            ->with(['department', 'approvedBy', 'rejectedBy', 'company', 'branch'])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('updated_at');

        // Apply the same filters as the report
        $this->applyDateRange($query, 'updated_at', $request);

        if ($request->filled('company_id') && auth()->user()->role === 'superadmin') {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $visitors = $query->get();

        return Excel::download(new VisitorsExport($visitors), 'approval-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function securityReportExport(Request $request)
    {
        $query = SecurityCheck::with(['visitor', 'staff'])->latest('created_at');

        if (!$this->isSuper()) {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $query->whereHas('visitor', function ($v) use ($u) {
                $v->where('company_id', $u->company_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $this->applyDateRange($query, 'created_at', $request);

        $checks = $query->get();

        $headings = [
            'Visitor Name',
            'Checkpoint',
            'Verification Method',
            'Status',
            'Reason',
            'Security Staff',
            'Verification Time',
            'Photo Clicked',
        ];

        $rows = $checks->map(function ($check) {
            return [
                optional($check->visitor)->name ?? '—',
                $check->checkpoint,
                $check->verification_method,
                $check->status,
                $check->reason ?? '—',
                $check->security_officer_name ?? '—',
                $check->created_at ? Carbon::parse($check->created_at)->format('Y-m-d h:i A') : '—',
                $check->photo_clicked ? 'Yes' : 'No',
            ];
        })->toArray();

        return Excel::download(
            new ArrayExport($headings, $rows),
            'security-checkpoints-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function hourlyReportExport(Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');
        $start = $from ? Carbon::parse($from)->startOfDay() : Carbon::today()->startOfDay();
        $end   = $to   ? Carbon::parse($to)->endOfDay()   : Carbon::today()->endOfDay();

        $selectedCompany = $request->input('company_id');
        $selectedBranch  = $request->input('branch_id');

        $inQ = Visitor::query();

        if ($this->isSuper()) {
            if ($selectedCompany) {
                $inQ->where('company_id', $selectedCompany);
            }
            if ($selectedBranch) {
                $inQ->where('branch_id', $selectedBranch);
            }
        } else {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $inQ->where('company_id', $u->company_id);
            if (!empty($u->branch_id)) {
                $inQ->where('branch_id', $u->branch_id);
            } elseif ($selectedBranch) {
                $inQ->where('branch_id', $selectedBranch);
            }
        }

        $inAgg = $inQ->whereBetween('in_time', [$start, $end])
            ->select(DB::raw("DATE_FORMAT(in_time, '%Y-%m-%d %H:00:00') as hour_slot"), DB::raw('COUNT(*) as total'))
            ->groupBy('hour_slot')
            ->pluck('total', 'hour_slot');

        $rows = [];
        $cursor = $start->copy()->startOfHour();
        $endHour = $end->copy()->startOfHour();
        while ($cursor <= $endHour) {
            $key = $cursor->format('Y-m-d H:00:00');
            $rows[] = [
                $cursor->format('Y-m-d'),
                $cursor->format('h A'),
                (int) ($inAgg[$key] ?? 0),
            ];
            $cursor->addHour();
        }

        $headings = ['Date', 'Hour', 'Total Visitors'];

        return Excel::download(
            new ArrayExport($headings, $rows),
            'visitor-hourly-' . now()->format('Ymd_His') . '.xlsx'
        );
    }
    // Approvals listing (non-report)
    public function approvals(Request $request)
    {
        $query = $this->companyScope(Visitor::with(['company', 'department', 'category']));

        if ($this->isCompany() && (auth()->user()->company?->auto_approve_visitors)) {
            $query->where('status', 'Approved');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $visitors = $query->latest()->paginate(10)->appends($request->query());

        $departments = Department::when(
            !$this->isSuper(),
            fn($q) => $q->where('company_id', auth()->user()->company_id)
        )->when(
            $request->filled('company_id'),
            fn($q) => $q->where('company_id', $request->company_id)
        )->orderBy('name')->get();

        // Prepare data for view
        $isSuper = $this->isSuper();
        $companies = [];
        
        if ($isSuper) {
            $companies = Company::orderBy('name')->pluck('name', 'id')->toArray();
        }

        return view('visitors.approvals', compact('visitors', 'departments', 'isSuper', 'companies'));
    }

    /**
     * Remove the specified visitor from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the visitor
            $visitor = Visitor::findOrFail($id);
            
            // Check if user has permission to delete this visitor
            if (!$this->isSuper() && $visitor->company_id !== auth()->user()->company_id) {
                return back()->with('error', 'You are not authorized to delete this visitor.');
            }
            
            // Delete the visitor
            $visitor->delete();
            
            return redirect()->route('visitors.index')
                ->with('success', 'Visitor deleted successfully');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting visitor: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint to check if face recognition is enabled for a company
     */
    public function checkFaceRecognition($companyId)
    {
        try {
            $company = Company::findOrFail($companyId);
            return response()->json([
                'enabled' => (bool) $company->face_recognition_enabled
            ]);
        } catch (\Exception $e) {
            return response()->json(['enabled' => false], 404);
        }
    }
}
