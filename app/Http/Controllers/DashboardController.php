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

    $from = $request->input('from', now()->subDays(30)->format('Y-m-d'));
    $to   = $request->input('to',   now()->format('Y-m-d'));

    // Ensure from <= to
    if (strtotime($from) > strtotime($to)) {
        [$from, $to] = [$to, $from];
    }

    // ----- Base visitor query (range) -----
    $visitorQuery = Visitor::query()
        ->whereDate('created_at', '>=', $from)
        ->whereDate('created_at', '<=', $to)
        ->with(['company', 'department', 'branch']);

    // Role-based filters
    if (($user->role ?? null) === 'company') {
        $visitorQuery->where('company_id', $user->company_id);

        if ($user->company?->auto_approve_visitors) {
            $visitorQuery->where('status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin' && $selectedCompany) {
        $visitorQuery->where('company_id', $selectedCompany);
    }

    // Extra filters
    if ($selectedBranch) {
        $visitorQuery->where('branch_id', $selectedBranch);
    }

    if ($selectedDepartment) {
        $visitorQuery->where('department_id', $selectedDepartment);
    }

    // ----- Summary counts -----
    $totalVisitors = (clone $visitorQuery)->count();
    $approvedCount = (clone $visitorQuery)->where('status', 'Approved')->count();
    $pendingCount  = (clone $visitorQuery)->where('status', 'Pending')->count();
    $rejectedCount = (clone $visitorQuery)->where('status', 'Rejected')->count();

    // ----- Latest visitors & table -----
    $latestVisitors = (clone $visitorQuery)->latest()->take(6)->get();

    $visitors = (clone $visitorQuery)
        ->latest()
        ->paginate(10);

    // ----- Monthly chart (whole year, filtered by company) -----
    $monthlyBase = Visitor::query()
        ->whereYear('created_at', now()->year);

    if (($user->role ?? null) === 'company') {
        $monthlyBase->where('company_id', $user->company_id);
        if ($user->company?->auto_approve_visitors) {
            $monthlyBase->where('status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin' && $selectedCompany) {
        $monthlyBase->where('company_id', $selectedCompany);
    }

    $monthly = $monthlyBase
        ->selectRaw("DATE_FORMAT(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30'), '%b') as month, COUNT(*) as count")
        ->groupBy('month')
        ->orderByRaw("STR_TO_DATE(month, '%b')")
        ->get();

    $chartLabels = $monthly->pluck('month');
    $chartData   = $monthly->pluck('count');

    // ----- Hourly chart -----
    $singleDay = ($from === $to);

    $hourBase = Visitor::query()
        ->when(($user->role ?? null) === 'company', function ($q) use ($user) {
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
    } else {
        // If multi-day range, show today's hours
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

    // ----- Day-wise chart (selected range) -----
    $dayWise = Visitor::query()
        ->when(($user->role ?? null) === 'company', function ($q) use ($user) {
            $q->where('company_id', $user->company_id);
            if ($user->company?->auto_approve_visitors) {
                $q->where('status', 'Approved');
            }
        })
        ->when(($user->role ?? null) === 'superadmin', function ($q) use ($selectedCompany) {
            if ($selectedCompany) {
                $q->where('company_id', $selectedCompany);
            }
        })
        ->whereRaw(
            "DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) BETWEEN ? AND ?",
            [$from, $to]
        )
        ->selectRaw("DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) as date, COUNT(*) as count")
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('count', 'date');

    $periodStart    = Carbon::parse($from);
    $periodEnd      = Carbon::parse($to);
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
        ->whereRaw(
            "DATE(CONVERT_TZ(COALESCE(visitors.in_time, visitors.created_at), '+00:00', '+05:30')) BETWEEN ? AND ?",
            [$from, $to]
        )
        ->groupBy('departments.name')
        ->orderBy('departments.name');

    if (($user->role ?? null) === 'company') {
        $deptQuery->where('visitors.company_id', $user->company_id);
        if ($user->company?->auto_approve_visitors) {
            $deptQuery->where('visitors.status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin' && $selectedCompany) {
        $deptQuery->where('visitors.company_id', $selectedCompany);
    }

    $deptData           = $deptQuery->get();
    $deptLabels         = $deptData->pluck('department');
    $deptCounts         = $deptData->pluck('total');
    $totalDeptVisitors  = $deptCounts->sum();

    // ----- Branches & Departments for filters -----
    $branches    = collect();
    $departments = collect();

    if (($user->role ?? null) === 'company') {
        $branches    = Branch::where('company_id', $user->company_id)->pluck('name', 'id');
        $departments = Department::where('company_id', $user->company_id)->pluck('name', 'id');
    } elseif (($user->role ?? null) === 'superadmin' && $selectedCompany) {
        $branches    = Branch::where('company_id', $selectedCompany)->pluck('name', 'id');
        $departments = Department::where('company_id', $selectedCompany)->pluck('name', 'id');
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
