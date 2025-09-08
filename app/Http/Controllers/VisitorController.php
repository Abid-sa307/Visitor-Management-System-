<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Company;
use App\Models\Department;
use App\Models\VisitorCategory;
use App\Models\SecurityCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitorController extends Controller
{
    /* --------------------------- Helpers --------------------------- */

    private function isSuper(): bool
    {
        return (auth()->user()->role ?? null) === 'superadmin';
    }

    private function isCompany(): bool
    {
        return (auth()->user()->role ?? null) === 'company';
    }

    // Scope queries to company for non-super admins
    private function companyScope($query)
    {
        if (!$this->isSuper()) {
            $query->where('company_id', auth()->user()->company_id);
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
        return $this->isSuper()
            ? Company::orderBy('name')->get()
            : Company::where('id', auth()->user()->company_id)->get();
    }

    private function getDepartments()
    {
        return $this->isSuper()
            ? Department::orderBy('name')->get()
            : Department::where('company_id', auth()->user()->company_id)->orderBy('name')->get();
    }

    private function authorizeVisitor($visitor)
    {
        if (!$this->isSuper() && $visitor->company_id != auth()->user()->company_id) {
            abort(403, 'Unauthorized access.');
        }
    }

    /* --------------------------- CRUD --------------------------- */

    public function index()
    {
        $visitors = $this->companyScope(Visitor::query()->latest())->paginate(10);
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
        $validated = $request->validate([
            'company_id'          => 'nullable|exists:companies,id',
            'name'                => 'required|string|max:255',
            'visitor_category_id' => 'nullable|exists:visitor_categories,id',
            'email'               => 'nullable|email',
            'phone'               => 'required|string|max:15',
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

        if (!$this->isSuper()) {
            $validated['company_id'] = auth()->user()->company_id;
        }

        if ($request->hasFile('photo')) {
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

        $validated['status'] = 'Pending';
        Visitor::create($validated);

        return redirect()->route($this->panelRoute('visitors.index'))
            ->with('success', 'Visitor registered successfully!');
    }

    public function edit(Visitor $visitor)
    {
        $this->authorizeVisitor($visitor);

        $companies   = $this->getCompanies();
        $departments = $this->getDepartments();
        $categories  = VisitorCategory::orderBy('name')->get();

        return view('visitors.edit', compact('visitor', 'companies', 'departments', 'categories'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $this->authorizeVisitor($visitor);

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
            $validated['company_id'] = auth()->user()->company_id;
        }

        if ($request->hasFile('photo')) {
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

        $visitor->update($validated);

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
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
            $request->merge(['company_id' => auth()->user()->company_id]);
        }

        $request->validate([
            'company_id'          => 'required|exists:companies,id',
            'department_id'       => 'required|exists:departments,id',
            'person_to_visit'     => 'required|string',
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
        $visitors = $this->companyScope(Visitor::query()->latest('created_at'))->paginate(10);
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
            $visitor->in_time = now();
            $visitor->status = 'Approved';
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
        return view('visitors.pass', compact('visitor'));
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
            $query->whereHas('visitor', function ($v) {
                $v->where('company_id', auth()->user()->company_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $this->applyDateRange($query, 'verification_time', $request);

        $checks = $query->paginate(10)->appends($request->query());

        return view('visitors.security_checkpoints', compact('checks'));
    }

    // Approvals listing (non-report)
    public function approvals(Request $request)
    {
        $query = $this->companyScope(Visitor::with(['company', 'department', 'category']));

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
