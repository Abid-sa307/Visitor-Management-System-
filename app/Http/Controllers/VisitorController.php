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
use App\Notifications\VisitorCreated;


class VisitorController extends Controller
{
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
        if (!$this->isSuper()) {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $query->where('company_id', $u->company_id);
            if (!empty($u->branch_id)) {
                $query->where('branch_id', $u->branch_id);
            }
        }
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
        $query = $this->companyScope(Visitor::query()->latest());
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
        $validated = $request->validate([
            'company_id'          => 'nullable|exists:companies,id',
            'name'                => 'required|string|max:255',
            'visitor_category_id' => 'nullable|exists:visitor_categories,id',
            'email'               => 'nullable|email',
            'phone'               => 'required|string|max:32',
            'photo'               => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        ]);

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
        // Handle photo: prefer base64 camera capture over file upload
        // ---------------------------
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

        // ---------------------------
        // Set status based on company auto-approval
        // ---------------------------
        $status = 'Pending';
        $approvedAt = null;

        if (!empty($validated['company_id'])) {
            $company = Company::find($validated['company_id']);
            if ($company && (int)$company->auto_approve_visitors === 1) {
                $status = 'Approved';
                $approvedAt = now();
            }
        }

        $validated['status'] = $status;
        if (\Schema::hasColumn('visitors', 'approved_at')) {
            $validated['approved_at'] = $approvedAt;
        }

        // ---------------------------
        // Create the visitor
        // ---------------------------
        $visitor = Visitor::create($validated);

        // Send email notification to visitor if email provided
        try {
            if (!empty($visitor->email)) {
                \Mail::to($visitor->email)->send(new \App\Mail\VisitorCreatedMail($visitor));
                // If auto-approved on creation, also send approved mail
                if ($visitor->status === 'Approved') {
                    \Mail::to($visitor->email)->send(new \App\Mail\VisitorApprovedMail($visitor));
                }
            }
        } catch (\Throwable $e) {
            // Avoid breaking flow if mail fails; consider logging
            // \Log::warning('VisitorCreated mail failed: '.$e->getMessage());
        }

        // ---------------------------
        // Notify company users (database notifications)
        // ---------------------------
        try {
            if (!empty($visitor->company_id)) {
                $recipients = User::query()
                    ->where('company_id', $visitor->company_id)
                    ->when(!empty($visitor->branch_id), function ($q) use ($visitor) {
                        $q->where(function ($qq) use ($visitor) {
                            // notify users assigned to this branch, or with no branch assignment (company-wide)
                            $qq->whereNull('branch_id')->orWhere('branch_id', $visitor->branch_id);
                        });
                    })
                    ->get();
                foreach ($recipients as $user) {
                    $user->notify(new VisitorCreated($visitor));
                }
            }
        } catch (\Throwable $e) {
            // Swallow notification errors so visitor creation isn't blocked
            // Consider logging if needed: \Log::warning('VisitorCreated notify failed: '.$e->getMessage());
        }

        // ---------------------------
        // Redirect based on user type
        // ---------------------------
        if ($this->isSuper()) {
            return redirect()->route('visitors.index')
                ->with('success', $status === 'Approved'
                    ? 'Visitor auto-approved successfully.'
                    : 'Visitor submitted for approval.');
        } else {
            return redirect()->route('company.visitors.index')
                ->with('success', $status === 'Approved'
                    ? 'Visitor auto-approved successfully.'
                    : 'Visitor submitted for approval.');
        }
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

    // If only status is being updated (Approve/Reject buttons)
    $nonBusiness = ['_token','_method'];
    $payloadCount = count($request->except($nonBusiness));
    $isAjax = $request->ajax() || $request->wantsJson();
    if ($request->has('status') && ($payloadCount === 1 || $isAjax)) {
        $wasApproved = $visitor->status === 'Approved';
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Completed',
        ]);

        $visitor->status = $request->input('status');
        $visitor->save();

        // If transitioned to Approved, send mail to visitor
        if (!$wasApproved && $visitor->status === 'Approved' && !empty($visitor->email)) {
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
    $validated = $request->validate([
        'company_id'          => 'required|exists:companies,id',
        'name'                => 'required|string|max:255',
        'visitor_category_id' => 'nullable|exists:visitor_categories,id',
        'email'               => 'nullable|email',
        'phone'               => 'required|string|max:15',
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
    ]);

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

    $wasApproved = $visitor->status === 'Approved';
    $visitor->update($validated);
    if (!$wasApproved && ($visitor->status === 'Approved') && !empty($visitor->email)) {
        try {
            \Mail::to($visitor->email)->send(new \App\Mail\VisitorApprovedMail($visitor));
        } catch (\Throwable $e) {
            // swallow error
        }
    }

    return redirect()->route($this->panelRoute('visitors.index'))
        ->with('success', 'Visitor updated successfully!');
}


  public function destroy(Visitor $visitor)
{
    $this->authorizeVisitor($visitor);
    $visitor->delete();

    // Redirect back to the list in the same panel
    $backTo = request()->is('company/*') ? 'company.visitors.index' : 'visitors.index';
    return redirect()->route($backTo)->with('success', 'Visitor deleted successfully!');
}

    /* --------------------------- Other flows --------------------------- */

    public function history(Request $request)
    {
        $query = $this->companyScope(Visitor::query());

        // Show all statuses in history for company users so nothing is hidden

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Superadmin may filter by specific company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Optional branch filter for users who can view multiple branches
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $this->applyDateRange($query, 'in_time', $request);

        $visitors = $query->latest()->paginate(10)->appends($request->query());
        $companies = $this->getCompanies();

        $departments = $request->filled('company_id')
            ? Department::where('company_id', $request->company_id)->orderBy('name')->get()
            : $this->getDepartments();

        return view('visitors.history', compact('visitors', 'companies', 'departments'));
    }

    public function visitForm($id)
    {
        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

        $companies   = $this->getCompanies();
        $departments = $this->getDepartments();
        return view('visitors.visit', compact('visitor', 'departments', 'companies'));
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
        return view('visitors.entry', compact('visitors'));
    }

    public function toggleEntry($id)
    {
        // If you later add guard role, leave this check; otherwise remove.
        if ((auth()->user()->role ?? null) === 'guard') {
            abort(403, 'Unauthorized action.');
        }

        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

        if (!$visitor->in_time) {
            // Only allow Mark In if already Approved OR company has auto-approve enabled
            $companyAuto = (bool) optional($visitor->company)->auto_approve_visitors;
            if (!$companyAuto && $visitor->status !== 'Approved') {
                return back()->with('error', 'Visitor must be approved before marking IN.');
            }
            $visitor->in_time = now();
            // Keep status as-is; do not auto-upgrade here unless needed
            if ($visitor->status === 'Pending' && $companyAuto) {
                $visitor->status = 'Approved';
            }
        } elseif (!$visitor->out_time) {
            $visitor->out_time = now();
            $visitor->status = 'Completed';
        }

        $visitor->save();

        return back()->with('success', 'Visitor entry updated.');
    }

    public function printPass($id)
    {
        $visitor = Visitor::with('company')->findOrFail($id);
        $this->authorizeVisitor($visitor);
        if ($visitor->status !== 'Approved') {
            return redirect()->back()->with('error', 'Pass is available only after the visitor is approved.');
        }
        return view('visitors.pass', compact('visitor'));
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
    public function report(Request $request)
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;

        $todayVisitors = $this->companyScope(
            Visitor::whereDate('created_at', $today)
        )->count();

        $monthVisitors = $this->companyScope(
            Visitor::whereMonth('created_at', $month)
        )->count();

        $statusCounts = $this->companyScope(
            Visitor::select('status', DB::raw('count(*) as total'))->groupBy('status')
        )->pluck('total', 'status');

        $query = $this->companyScope(Visitor::query())->latest();

        $this->applyDateRange($query, 'in_time', $request);

        $visitors = $query->paginate(10)->appends($request->query());

        return view('visitors.report', compact('visitors', 'todayVisitors', 'monthVisitors', 'statusCounts'));
    }

    // Visitor In/Out Report (range across in_time OR out_time)
    public function inOutReport(Request $request)
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

        $visitors = $query->latest('in_time')->paginate(10)->appends($request->query());

        return view('visitors.visitor_inout', compact('visitors'));
    }

    // Approval Status Report (filter by department + date range on updated_at; change to approved_at if you add that)
    public function approvalReport(Request $request)
    {
        $query = $this->companyScope(Visitor::with(['department']))->latest('updated_at');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $this->applyDateRange($query, 'updated_at', $request);

        $visitors    = $query->paginate(10)->appends($request->query());
        $departments = $this->getDepartments();

        return view('visitors.approval_status', compact('visitors', 'departments'));
    }

    // Security Checkpoints Report (filter by verification_time; company via related visitor)
    public function securityReport(Request $request)
    {
        $query = SecurityCheck::with(['visitor', 'staff'])->latest('verification_time');

        if (!$this->isSuper()) {
            $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
            $query->whereHas('visitor', function ($v) use ($u) {
                $v->where('company_id', $u->company_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $this->applyDateRange($query, 'verification_time', $request);

        $checks = $query->paginate(10)->appends($request->query());

        return view('visitors.security_checkpoints', compact('checks'));
    }

    // Hourly visitors report (counts of in/out per hour over a date range)
    public function hourlyReport(Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');
        $start = $from ? Carbon::parse($from)->startOfDay() : Carbon::today()->startOfDay();
        $end   = $to   ? Carbon::parse($to)->endOfDay()   : Carbon::today()->endOfDay();

        $selectedCompany = $request->input('company_id');
        $selectedBranch  = $request->input('branch_id');

        // Base query (hourly counts based on in_time)
        $inQ = Visitor::query();

        // Scope by role/company/branch
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

        // Apply range for in_time and aggregate hourly counts
        $inAgg = $inQ->whereBetween('in_time', [$start, $end])
            ->select(DB::raw("DATE_FORMAT(in_time, '%Y-%m-%d %H:00:00') as hour_slot"), DB::raw('COUNT(*) as total'))
            ->groupBy('hour_slot')
            ->pluck('total', 'hour_slot');

        // Build full hourly series
        $series = [];
        $cursor = $start->copy()->startOfHour();
        $endHour = $end->copy()->startOfHour();
        while ($cursor <= $endHour) {
            $key = $cursor->format('Y-m-d H:00:00');
            $series[] = [
                'hour'  => $key,
                'count' => (int)($inAgg[$key] ?? 0),
            ];
            $cursor->addHour();
        }

        $companies = $this->getCompanies();

        // Branches list for superadmin if company selected; for company users, we can show all their branches
        $branches = Branch::query()
            ->when(!$this->isSuper(), function($q){
                $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
                $q->where('company_id', $u->company_id);
            })
            ->when($this->isSuper() && $selectedCompany, fn($q)=>$q->where('company_id', $selectedCompany))
            ->orderBy('name')
            ->get();

        return view('visitors.reports_hourly', [
            'series'          => $series,
            'from'            => $start->format('Y-m-d'),
            'to'              => $end->format('Y-m-d'),
            'companies'       => $companies,
            'branches'        => $branches,
            'selectedCompany' => $selectedCompany,
            'selectedBranch'  => $selectedBranch,
        ]);
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
}
