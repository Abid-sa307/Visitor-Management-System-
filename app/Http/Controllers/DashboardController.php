<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the company dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function companyDashboard(Request $request)
    {
        return $this->index($request);
    }
    /**
     * Display the company dashboard.
     *
     * @return \Illuminate\View\View
     */
public function index(Request $request)
{
    // Check which guard is authenticated
    $isCompanyUser = Auth::guard('company')->check();
    $user = $isCompanyUser ? Auth::guard('company')->user() : Auth::user();
    
    // Set auto-approve setting for company users
    $autoApprove = false;
    if ($isCompanyUser && $user->company) {
        $autoApprove = (bool) $user->company->auto_approve_visitors;
    }
    
    // Set user type for view
    $userType = $isCompanyUser ? 'company' : 'admin';

    // ----- Filters -----
    $selectedCompany   = $request->input('company_id');
    $selectedBranch    = $request->input('branch_id');
    $selectedDepartment= $request->input('department_id');

    // Handle array inputs for multi-select
    $branchIds = is_array($selectedBranch) ? $selectedBranch : ($selectedBranch ? [$selectedBranch] : []);
    $departmentIds = is_array($selectedDepartment) ? $selectedDepartment : ($selectedDepartment ? [$selectedDepartment] : []);

    // Date range (optional)
    $from = $request->input('from');
    $to   = $request->input('to');

    if ($from && !$to) {
        $to = $from;
    } elseif ($to && !$from) {
        $from = $to;
    }

    if ($from && $to && strtotime($from) > strtotime($to)) {
        [$from, $to] = [$to, $from];
    }

    // ----- Base visitor query (role + extra filters, date optional) -----
    $baseVisitorQuery = Visitor::query();

    if (in_array(($user->role ?? null), ['company', 'company_user'])) {
        $baseVisitorQuery->where('company_id', $user->company_id);

        if ($user->company?->auto_approve_visitors) {
            $baseVisitorQuery->where('status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin' && $selectedCompany) {
        $baseVisitorQuery->where('company_id', $selectedCompany);
    }

    if (!empty($branchIds)) {
        $baseVisitorQuery->whereIn('branch_id', $branchIds);
    }

    if (!empty($departmentIds)) {
        $baseVisitorQuery->whereIn('department_id', $departmentIds);
    }

    $dateFilteredQuery = clone $baseVisitorQuery;

    if ($from && $to) {
        $dateFilteredQuery
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);
    }

    $visitorQuery = (clone $dateFilteredQuery)->with(['company', 'department', 'branch']);

    // ----- Summary counts -----
    // If no date range is specified, default to today's visitors
    if (!$from && !$to) {
        $dateFilteredQuery->whereDate('created_at', Carbon::today());
    }
    
    $totalVisitors = (clone $dateFilteredQuery)->count();
    $approvedCount = (clone $dateFilteredQuery)->where('status', 'Approved')->count();
    $pendingCount  = (clone $dateFilteredQuery)->where('status', 'Pending')->count();
    $rejectedCount = (clone $dateFilteredQuery)->where('status', 'Rejected')->count();

    // Store the date range in the session for the view
    $request->session()->flash('date_range', [
        'from' => $from,
        'to' => $to
    ]);

    // Pass the date range to the view
    $dateRange = [
        'from' => $from,
        'to' => $to
    ];

    // ----- Latest visitors & table -----
    $latestVisitors = (clone $visitorQuery)->latest()->take(6)->get();

    $visitors = (clone $visitorQuery)
        ->latest()
        ->paginate(10);

    // ----- Monthly chart (respect date range) -----
    $monthlyBase = Visitor::query();

    if (in_array(($user->role ?? null), ['company', 'company_user'])) {
        $monthlyBase->where('company_id', $user->company_id);
        if ($user->company?->auto_approve_visitors) {
            $monthlyBase->where('status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin' && $selectedCompany) {
        $monthlyBase->where('company_id', $selectedCompany);
    }

    // Apply date range to monthly chart
    if ($from && $to) {
        $monthlyBase->whereDate('created_at', '>=', $from)
                   ->whereDate('created_at', '<=', $to);
    } else {
        // If no date range, default to current year
        $monthlyBase->whereYear('created_at', now()->year);
    }

    $monthly = $monthlyBase
        ->selectRaw("DATE_FORMAT(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30'), '%b') as month, COUNT(*) as count")
        ->groupBy('month')
        ->orderByRaw("STR_TO_DATE(month, '%b')")
        ->get();

    $chartLabels = $monthly->pluck('month');
    $chartData   = $monthly->pluck('count');

    // ----- Hourly chart -----
    $singleDay = ($from && $to && $from === $to);

    $hourBase = Visitor::query()
        ->when(in_array(($user->role ?? null), ['company', 'company_user']), function ($q) use ($user) {
            $q->where('company_id', $user->company_id);
            if ($user->company?->auto_approve_visitors) {
                $q->where('status', 'Approved');
            }
        })
        ->when(($user->role ?? null) === 'superadmin', function ($q) use ($selectedCompany) {
            if ($selectedCompany) {
                $q->where('company_id', $selectedCompany);
            }
        });

    if ($singleDay) {
        $hourBase->whereRaw(
            "DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) = ?",
            [$from]
        );
    } elseif ($from && $to) {
        // For multi-day range, aggregate hours across all days
        $hourBase->whereRaw(
            "DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) BETWEEN ? AND ?",
            [$from, $to]
        );
    } else {
        // If no date range, show today's hours
        $hourBase->whereRaw(
            "DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) = CURDATE()"
        );
    }

    $hourly = $hourBase
        ->selectRaw("HOUR(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) as hour, COUNT(*) as count")
        ->groupBy('hour')
        ->orderBy('hour')
        ->get()
        ->keyBy('hour');

    $hourLabels = [];
    $hourData   = [];
    for ($i = 0; $i <= 23; $i++) {
        $hourLabels[] = sprintf('%02d:00', $i);
        $hourData[]   = isset($hourly[$i]) ? (int) $hourly[$i]->count : 0;
    }

    // ----- Day-wise chart -----
    $dayWiseBase = clone $dateFilteredQuery;

    $dayWise = (clone $dayWiseBase)
        ->selectRaw("DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) as date, COUNT(*) as count")
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('count', 'date');

    if ($from && $to) {
        $periodStart = Carbon::parse($from);
        $periodEnd   = Carbon::parse($to);
    } else {
        $firstRecord = (clone $dayWiseBase)->orderBy('created_at')->value('created_at');
        $lastRecord  = (clone $dayWiseBase)->orderByDesc('created_at')->value('created_at');

        if ($firstRecord && $lastRecord) {
            $periodStart = Carbon::parse($firstRecord);
            $periodEnd   = Carbon::parse($lastRecord);
        } else {
            $periodStart = now();
            $periodEnd   = now();
        }
    }
    $dayWiseLabels  = [];
    $dayWiseData    = [];

    for ($d = $periodStart->copy(); $d->lte($periodEnd); $d->addDay()) {
        $key = $d->format('Y-m-d');
        $dayWiseLabels[] = $d->format('d M');
        $dayWiseData[]   = $dayWise[$key] ?? 0;
    }

    // ----- Department-wise chart -----
    $deptQuery = DB::table('visitors')
        ->join('departments', 'visitors.department_id', '=', 'departments.id')
        ->select('departments.name as department', DB::raw('COUNT(*) as total'))
        ->groupBy('departments.name')
        ->orderBy('departments.name');

    if ($from && $to) {
        $deptQuery->whereRaw(
            "DATE(CONVERT_TZ(COALESCE(visitors.in_time, visitors.created_at), '+00:00', '+05:30')) BETWEEN ? AND ?",
            [$from, $to]
        );
    }

    if (in_array(($user->role ?? null), ['company', 'company_user'])) {
        $deptQuery->where('visitors.company_id', $user->company_id);
        if ($user->company?->auto_approve_visitors) {
            $deptQuery->where('visitors.status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin' && $selectedCompany) {
        $deptQuery->where('visitors.company_id', $selectedCompany);
    }

    // Apply branch and department filters to chart
    if (!empty($branchIds)) {
        $deptQuery->whereIn('visitors.branch_id', $branchIds);
    }

    if (!empty($departmentIds)) {
        $deptQuery->whereIn('visitors.department_id', $departmentIds);
    }

    $deptData           = $deptQuery->get();
    $deptLabels         = $deptData->pluck('department');
    $deptCounts         = $deptData->pluck('total');
    $totalDeptVisitors  = $deptCounts->sum();

    // ----- Branches & Departments for filters -----
    $branches    = collect();
    $departments = collect();

    if (in_array(($user->role ?? null), ['company', 'company_user'])) {
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
        
        // If company has no branches, add a "None" option
        if ($branches->isEmpty()) {
            $branches = collect(['none' => 'None']);
        }
    } elseif (($user->role ?? null) === 'superadmin' && $selectedCompany) {
        $branches    = Branch::where('company_id', $selectedCompany)->pluck('name', 'id');
        $departments = Department::where('company_id', $selectedCompany)->pluck('name', 'id');
        
        // If selected company has no branches, add a "None" option
        if ($branches->isEmpty()) {
            $branches = collect(['none' => 'None']);
        }
    }

    // ----- Companies for filter -----
    if (($user->role ?? null) === 'superadmin') {
        $companies = Company::orderBy('name')->pluck('name', 'id');
    } else {
        $companies = Company::where('id', $user->company_id ?? null)
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    $company = $selectedCompany ? Company::find($selectedCompany) : null;

    return view('dashboard', [
        'autoApprove'       => $autoApprove,
        'totalVisitors'     => $totalVisitors,
        'approvedCount'     => $approvedCount,
        'pendingCount'      => $pendingCount,
        'rejectedCount'     => $rejectedCount,
        'latestVisitors'    => $latestVisitors,
        'visitors'          => $visitors,

        'chartLabels'       => $chartLabels,
        'chartData'         => $chartData,
        'hourLabels'        => $hourLabels,
        'hourData'          => $hourData,
        'dayWiseLabels'     => $dayWiseLabels,
        'dayWiseData'       => $dayWiseData,
        'deptLabels'        => $deptLabels,
        'deptCounts'        => $deptCounts,
        'totalDeptVisitors' => $totalDeptVisitors,

        'companies'         => $companies,
        'company'           => $company,
        'branches'          => $branches,
        'departments'       => $departments,
        'selectedCompany'   => $selectedCompany,
        'from'              => $from,
        'to'                => $to,
    ]);
}

}
