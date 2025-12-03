<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Company;
use App\Models\User;
use App\Models\Department;
use App\Models\VisitorCategory;
use App\Models\SecurityCheck;
use App\Models\Branch;
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


class VisitorController extends Controller
{
    
    private const NAME_REGEX = '/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'\-\.]+$/u';
    private const PHONE_REGEX = '/^\+?[0-9]{7,15}$/';

    /* --------------------------- Helpers --------------------------- */

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

    private function getDepartments()
    {
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

    public function create()
    {
        $companies   = $this->getCompanies();
        $departments = $this->getDepartments();
        $categories  = VisitorCategory::orderBy('name')->get();

        return view('visitors.create', compact('companies', 'departments', 'categories'));
    }

    
public function store(Request $request)
{
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
            'name'                => 'required|string|max:255|regex:' . self::NAME_REGEX,
            'visitor_category_id' => 'nullable|exists:visitor_categories,id',
            'email'               => 'nullable|email:rfc,dns',
            'phone'               => 'required|regex:' . self::PHONE_REGEX,
            'photo'               => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'face_image'          => 'nullable|string', // base64 webcam photo
            'face_encoding'       => 'nullable|json',   // Face descriptor array
            'department_id'       => 'nullable|exists:departments,id',
            'purpose'             => 'nullable|string|max:255',
            'person_to_visit'     => 'nullable|string|max:255',
            'documents'           => 'nullable|array',
            'documents.*'         => 'file|max:5120',
            'visitor_company'     => 'nullable|string|max:255',
            'visitor_website'     => 'nullable|string|max:255',
            'vehicle_type'        => 'nullable|string|max:20',
            'vehicle_number'      => 'nullable|string|max:50',
            'goods_in_car'        => 'nullable|string|max:255',
            'workman_policy'      => 'nullable|in:Yes,No',
            'workman_policy_photo'=> 'nullable|image|max:2048',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'document' => 'sometimes|file|mimes:pdf,doc,docx,jpeg,png|max:5120', // 5MB max
        ], $messages);

        $validated['name'] = Str::squish($validated['name']);
        if (!empty($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
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
        // Handle photo / face image
        // ---------------------------
        $faceData = null;
        
        // Log all request data for debugging
        \Log::info('Request data:', [
            'all' => $request->all(),
            'has_face_encoding' => $request->has('face_encoding'),
            'has_face_image' => $request->has('face_image'),
            'files' => $request->allFiles()
        ]);

        // Process face encoding if provided
        $faceData = null;
        if ($request->has('face_encoding') && !empty($request->face_encoding)) {
            \Log::info('Raw face_encoding from request:', [
                'face_encoding' => $request->face_encoding,
                'type' => gettype($request->face_encoding),
                'length' => is_string($request->face_encoding) ? strlen($request->face_encoding) : 'N/A'
            ]);
            
            // Try to decode the JSON
            $faceData = json_decode($request->face_encoding, true);
            $jsonError = json_last_error();
            
            if ($jsonError !== JSON_ERROR_NONE) {
                $errorMsg = 'JSON decode error: ' . json_last_error_msg() . ' (code: ' . $jsonError . ')';
                \Log::error($errorMsg, [
                    'input' => substr($request->face_encoding, 0, 100) . (strlen($request->face_encoding) > 100 ? '...' : '')
                ]);
                
                throw ValidationException::withMessages([
                    'face_encoding' => ['Invalid face data format. Please try capturing your face again.']
                ]);
            }
            
            \Log::info('Decoded face_encoding:', [
                'is_array' => is_array($faceData),
                'count' => is_array($faceData) ? count($faceData) : 'N/A',
                'sample' => is_array($faceData) ? array_slice($faceData, 0, 5) : 'N/A'
            ]);
            
            // Add to validated data to be saved - store as JSON string
            $validated['face_encoding'] = json_encode($faceData);
            \Log::info('Face encoding added to validated data', [
                'first_5_values' => array_slice($faceData, 0, 5),
                'validated_keys' => array_keys($validated),
                'stored_value' => $validated['face_encoding']
            ]);
        } else {
            \Log::warning('No face_encoding found in request', [
                'request_keys' => array_keys($request->all()),
                'request_has_face_encoding' => $request->has('face_encoding'),
                'face_encoding_empty' => $request->has('face_encoding') ? 'empty' : 'not present'
            ]);
        }


        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('visitor_photos', 'public');
            $visitor->photo_path = $path;
        }

        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('visitor_documents', 'public');
            $visitor->document_path = $documentPath;
        }


        // Handle document upload
        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $documentPath = $document->store('documents', 'public');
            $validated['document_path'] = $documentPath;
        }
        // Handle face image (base64)
        if ($request->filled('face_image')) {
            $dataUrl = $request->input('face_image');
            if (preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $dataUrl, $m)) {
                $ext = $m[1] === 'jpeg' ? 'jpg' : $m[1];
                $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
                $imageData = base64_decode($data);
                
                if ($imageData === false) {
                    throw new \Exception('Invalid base64 image data');
                }
                
                // Generate a unique filename with timestamp
                $filename = 'visitor_faces/visitor_face_' . time() . '_' . uniqid() . '.' . $ext;
                
                // Ensure the directory exists
                Storage::disk('public')->makeDirectory('visitor_faces');
                
                // Save the file to storage
                Storage::disk('public')->put($filename, $imageData);
                
                // Store the path in the database (not the base64 data)
                $validated['face_image'] = $filename;  // Store the path, not the base64 data
                
                // Also set as the main photo if no other photo was uploaded
                if (empty($validated['photo'])) {
                    $validated['photo'] = $filename;
                }
                
                // If we have face encoding, it's already JSON encoded and added to $validated
                // No need to set it again as it would overwrite the JSON with an array
                
                \Log::info('Face image saved successfully', [
                    'filename' => $filename,
                    'path' => $filename,
                    'size' => strlen($imageData) . ' bytes'
                ]);
            }
        }
        // Handle regular photo upload
        elseif ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        // Handle documents upload
        if ($request->hasFile('documents')) {
            $paths = [];
            foreach ($request->file('documents') as $doc) {
                $paths[] = $doc->store('documents', 'public');
            }
            $validated['documents'] = $paths;
        }

        // Workman policy photo
        if ($request->hasFile('workman_policy_photo')) {
            $validated['workman_policy_photo'] = $request->file('workman_policy_photo')
                ->store('wpc_photos', 'public');
        }

        // Auto-approval logic
        $status = 'Pending';
        $approvedAt = null;

        if (!empty($validated['company_id'])) {
            $company = Company::find($validated['company_id']);
            if ($company && (int) $company->auto_approve_visitors === 1) {
                $status = 'Approved';
                $approvedAt = now();
            }
        }

        $validated['status'] = $status;
        if (\Schema::hasColumn('visitors', 'approved_at')) {
            $validated['approved_at'] = $approvedAt;
        }

        // Create visitor
        $visitor = Visitor::create($validated);

        // Attach documents if any
        if (isset($validated['documents'])) {
            foreach ($validated['documents'] as $documentPath) {
                $visitor->documents()->create([
                    'file_path' => $documentPath,
                    'file_name' => basename($documentPath)
                ]);
            }
        }

        // Email notifications
        try {
            if (!empty($visitor->email)) {
                \Mail::to($visitor->email)->send(new \App\Mail\VisitorCreatedMail($visitor));
                if ($visitor->status === 'Approved') {
                    \Mail::to($visitor->email)->send(new \App\Mail\VisitorApprovedMail($visitor));
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('VisitorCreated mail failed: '.$e->getMessage());
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
            }
        } catch (\Throwable $e) {
            \Log::warning('VisitorCreated notify failed: '.$e->getMessage());
        }

        // Redirect based on user type
        $route = $this->isSuper() ? 'visitors.index' : 'company.visitors.index';
        $message = $status === 'Approved' 
            ? 'Visitor registered and auto-approved successfully.' 
            : 'Visitor registered successfully. Pending approval.';

        return redirect()->route($route)->with('success', $message);
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
        $categories = VisitorCategory::orderBy('name')->get();

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
                $visitor->document_path = $documentPath;
            }

            return redirect()->back()->with('success', $message);
        }

        // If only status is being updated (Approve/Reject buttons)
        $nonBusiness = ['_token','_method'];
        $payloadCount = count($request->except($nonBusiness));
        $isAjax = $request->ajax() || $request->wantsJson();
        if ($request->has('status') && ($payloadCount === 1 || $isAjax)) {
            $request->validate([
                'status' => 'required|in:Pending,Approved,Rejected,Completed',
            ]);

            $previousStatus = $visitor->status;
            $visitor->last_status = $previousStatus;
            $visitor->status_changed_at = now();
            $visitor->status = $request->input('status');
            $visitor->save();

            // If transitioned to Approved, send mail to visitor
            if ($previousStatus !== 'Approved' && $visitor->status === 'Approved' && !empty($visitor->email)) {
                try {
                    \Mail::to($visitor->email)->send(new \App\Mail\VisitorApprovedMail($visitor));
                } catch (\Throwable $e) {
                    // swallow error
                }
            }

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'status'  => $visitor->status,
                    'message' => "Visitor status updated to {$visitor->status}",
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
        $visitor->fill($validated);
        if (array_key_exists('status', $validated) && $validated['status'] !== $previousStatus) {
            $visitor->last_status = $previousStatus;
            $visitor->status_changed_at = now();
        }
        $visitor->save();

        if ($previousStatus !== 'Approved' && ($visitor->status === 'Approved') && !empty($visitor->email)) {
            try {
                \Mail::to($visitor->email)->send(new \App\Mail\VisitorApprovedMail($visitor));
            } catch (\Throwable $e) {
                // swallow error
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
            $q->where('branch_id', $request->branch_id);
        })
        ->when($request->filled('department_id'), function($q) use ($request) {
            $q->where('department_id', $request->department_id);
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

    $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
    $isSuper = $this->isSuper();
    
    // Get companies - all for super admin, only user's company for others
    $companies = $isSuper ? $this->getCompanies() : collect([$user->company]);
    
    // Get departments for the visitor's company or the first company if not set
    $companyId = $visitor->company_id ?? ($companies->first()->id ?? null);
    $departments = $companyId ? $this->getDepartments($companyId) : collect();
    
    // Get branches for the company
    $branchesQuery = Branch::query();
    if ($companyId) {
        $branchesQuery->where('company_id', $companyId);
    }
    $branches = $branchesQuery->orderBy('name')->get();
    
    // Get visitor categories for the company
    $visitorCategories = \App\Models\VisitorCategory::where('company_id', $companyId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();
    
    return view('visitors.visit', [
        'visitor' => $visitor,
        'departments' => $departments,
        'companies' => $companies,
        'branches' => $branches,
        'visitorCategories' => $visitorCategories,
        'isSuper' => $isSuper,
        'user' => $user
    ]);
}



   public function submitVisit(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

        if (!$this->isSuper()) {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $request->merge(['company_id' => $u->company_id]);
        }

        $request->validate([
            'company_id'          => 'required|exists:companies,id',
            'department_id'       => 'required|exists:departments,id',
            'branch_id'           => 'nullable|exists:branches,id',
            'person_to_visit'     => 'required|string',
            'purpose'             => 'nullable|string',
            'visitor_company'     => 'nullable|string',
            'visitor_website'     => 'nullable|url',
            'vehicle_type'        => 'nullable|string',
            'vehicle_number'      => 'nullable|string',
            'goods_in_car'        => 'nullable|string',
            'workman_policy'      => 'nullable|in:Yes,No',
            'workman_policy_photo'=> 'nullable|image|max:2048',
            'status'              => 'required|in:Pending,Approved,Rejected',
        ]);

        if ($request->hasFile('workman_policy_photo')) {
            $visitor->workman_policy_photo = $request->file('workman_policy_photo')->store('wpc_photos', 'public');
        }

        $visitor->update($request->except('workman_policy_photo') + [
            'workman_policy_photo' => $visitor->workman_policy_photo ?? null,
        ]);

        return redirect()->route($this->panelRoute('visitors.index'))
            ->with('success', 'Visit submitted successfully.');
    }

    public function entryPage()
    {
        $visitors = $this->companyScope(Visitor::query()->with(['company','department'])->latest('created_at'))->paginate(10);
        $isCompany = $this->isCompany();
        return view('visitors.entry', compact('visitors', 'isCompany'));
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
                
                $companyAuto = (bool) optional($visitor->company)->auto_approve_visitors;
                if (!$companyAuto && $visitor->status !== 'Approved') {
                    return redirect()->route('company.visitors.entry.page')
                        ->with('error', 'Visitor must be approved before checking in.');
                }
                
                $visitor->in_time = now();
                $visitor->status = $visitor->status === 'Pending' && $companyAuto ? 'Approved' : $visitor->status;
                $message = 'Visitor checked in successfully.';
            } else {
                // Check if already checked out
                if ($visitor->out_time) {
                    return redirect()->route('company.visitors.entry.page')
                        ->with('error', 'Visitor has already been checked out.');
                }
                
                $visitor->out_time = now();
                $visitor->status = 'Completed';
                $message = 'Visitor checked out successfully.';
            }
            
            $visitor->save();
            DB::commit();
            
            return redirect()->route('company.visitors.entry.page')
                ->with('success', $message);
                
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
     * @return \Illuminate\Http\RedirectResponse
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
            abort(403, 'Unauthorized action.');
        }

        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

        // Check if this is a face verification request
        $isFaceVerification = request()->has('face_verification') && request()->input('face_verification') === '1';
        $skipFaceVerification = request()->has('skip_face_verification') && request()->input('skip_face_verification') === '1';
        $faceVerified = request()->input('face_verified') === '1';

        // If face verification is required but not completed
        if ($visitor->face_encoding && !$skipFaceVerification && !$faceVerified) {
            return redirect()->route('visitors.entry.page')
                ->with('warning', 'Face verification is required for this visitor. Please use the "Check In/Out with Face" button.');
        }

        $originalStatus = $visitor->status;
        $isCheckingIn = !$visitor->in_time;
        
        try {
            DB::beginTransaction();

            if ($isCheckingIn) {
                // Only allow Check In if already Approved OR company has auto-approve enabled
                $companyAuto = (bool) optional($visitor->company)->auto_approve_visitors;
                if (!$companyAuto && $visitor->status !== 'Approved') {
                    return back()->with('error', 'Visitor must be approved before checking in.');
                }
                
                $visitor->in_time = now();
                $visitor->status = $visitor->status === 'Pending' && $companyAuto ? 'Approved' : $visitor->status;
                
                // Log the check-in
                activity()
                    ->performedOn($visitor)
                    ->withProperties([
                        'face_verified' => $faceVerified,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ])
                    ->log('checked in' . ($faceVerified ? ' with face verification' : ''));
                
                $message = 'Visitor checked in successfully' . ($faceVerified ? ' with face verification' : '') . '.';
            } else {
                // Check if already checked out
                if ($visitor->out_time) {
                    return back()->with('error', 'Visitor has already been checked out.');
                }
                
                $visitor->out_time = now();
                $visitor->status = 'Completed';
                
                // Log the check-out
                activity()
                    ->performedOn($visitor)
                    ->withProperties([
                        'face_verified' => $faceVerified,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ])
                    ->log('checked out' . ($faceVerified ? ' with face verification' : ''));
                
                $message = 'Visitor checked out successfully' . ($faceVerified ? ' with face verification' : '') . '.';
            }

            // Update status history if status changed
            if ($visitor->isDirty('status')) {
                $visitor->last_status = $originalStatus;
                $visitor->status_changed_at = now();
                
                // Log status change
                activity()
                    ->performedOn($visitor)
                    ->withProperties([
                        'old_status' => $originalStatus,
                        'new_status' => $visitor->status
                    ])
                    ->log('status changed');
            }
            
            // Save the changes
            $visitor->save();
            
            // Commit the transaction
            DB::commit();
            
            // Always redirect back to the previous URL with the success message
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            
            // Log the error
            \Log::error('Error toggling visitor entry: ' . $e->getMessage(), [
                'visitor_id' => $id,
                'exception' => $e
            ]);
            
            return back()->with('error', 'An error occurred while updating the visitor. Please try again.');
        }
    }

    public function printPass($id)
{
    $visitor = Visitor::with(['company', 'department', 'branch'])->findOrFail($id);
    $this->authorizeVisitor($visitor);
    
    if ($visitor->status !== 'Approved') {
        return redirect()->back()->with('error', 'Pass is available only after the visitor is approved.');
    }
    
    return view('visitors.pass', [
        'visitor' => $visitor,
        'company' => $visitor->company
    ]);
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
            // Intentionally exclude any photo fields
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
        $query->where('department_id', $request->department_id);
    }
    
    // Apply branch filter if provided
    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
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
    $query = $this->companyScope(Visitor::query())->with(['company', 'department', 'branch']);

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
        $query->where('department_id', $request->department_id);
    }

    // Apply branch filter
    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
    }

    $visits = $query->latest('in_time')->paginate(20);
    
    // Get filter data
    $companies = $this->getCompanies();
    $departments = $request->filled('company_id') 
        ? Department::where('company_id', $request->company_id)->pluck('name', 'id')->toArray()
        : [];
    $branches = $request->filled('company_id')
        ? Branch::where('company_id', $request->company_id)->pluck('name', 'id')->toArray()
        : [];

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
        ->whereIn('status', ['approved', 'rejected'])
        ->latest('updated_at');

    // Apply date range filter
    $this->applyDateRange($query, 'updated_at', $request);

    // Apply company filter if provided and user is superadmin
    if ($request->filled('company_id') && auth()->user()->role === 'superadmin') {
        $query->where('company_id', $request->company_id);
    }

    // Apply department filter if provided
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }
    
    // Apply branch filter if provided
    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
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
        ->select(
            DB::raw('HOUR(in_time) as hour'),
            DB::raw('DATE(in_time) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('hour', 'date')
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
        $query->where('company_id', $request->company_id);
    }

    // Apply department filter
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    // Apply branch filter
    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
    }

    // Apply company filter for non-superadmins
    if (auth()->user()->role !== 'superadmin') {
        $query->where('company_id', auth()->user()->company_id);

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

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $visitors = $query->latest()->paginate(10)->appends($request->query());

        $departments = Department::when(
            !$this->isSuper(),
            fn($q) => $q->where('company_id', auth()->user()->company_id)
        )->orderBy('name')->get();

        return view('visitors.approvals', compact('visitors', 'departments'));
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
}
