<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    // Prefer company guard when authenticated under company panel
    $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();

    $autoApprove = false;
    if (($user->role ?? null) === 'company' && method_exists($user, 'company') && $user->company) {
        $autoApprove = (bool) $user->company->auto_approve_visitors;
    }

    $selectedCompany = $request->company_id ?? null;
    $selectedBranch  = $request->branch_id ?? null;
    $from = $request->input('from');
    $to   = $request->input('to');
    
    // If no dates provided, default to last 30 days
    if (!$from && !$to) {
        $to = today()->toDateString();
        $from = today()->subDays(30)->toDateString();
    } elseif ($from && !$to) {
        $to = $from;
    } elseif ($to && !$from) {
        $from = $to;
    }

    // ------------------------ Base visitor query (range) ------------------------
    $visitorQuery = Visitor::query()
        ->whereDate('created_at', '>=', $from)
        ->whereDate('created_at', '<=', $to)
        ->with(['company', 'department', 'branch']);

    // Apply role-based filters
    if (($user->role ?? null) === 'company') {
        $visitorQuery->where('company_id', $user->company_id);
        
        if (!empty($user->branch_id)) {
            $visitorQuery->where('branch_id', $user->branch_id);
        } elseif ($selectedBranch) {
            $visitorQuery->where('branch_id', $selectedBranch);
        }
        
        if ($user->company?->auto_approve_visitors) {
            $visitorQuery->where('status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin') {
        if ($selectedCompany) {
            $visitorQuery->where('company_id', $selectedCompany);
            if ($selectedBranch) {
                $visitorQuery->where('branch_id', $selectedBranch);
            }
        }
    }

    // ------------------------ Summary counts ------------------------
    $approvedCount = (clone $visitorQuery)->where('status', 'Approved')->count();
    $pendingCount  = (clone $visitorQuery)->where('status', 'Pending')->count();
    $rejectedCount = (clone $visitorQuery)->where('status', 'Rejected')->count();

    // ------------------------ Latest visitors (within range) ------------------------
    $latestVisitors = (clone $visitorQuery)->latest()->take(6)->get();
    
    // Get paginated visitors for the visitors list
    $visitors = (clone $visitorQuery)
        ->with(['company', 'department'])
        ->latest()
        ->paginate(10);

    // ------------------------ Monthly chart (current year, not limited to range) ------------------------
    $monthlyBase = Visitor::query()
        ->whereYear('created_at', now()->year);
        
    if (($user->role ?? null) === 'company') {
        $monthlyBase->where('company_id', $user->company_id);
        
        if (!empty($user->branch_id)) {
            $monthlyBase->where('branch_id', $user->branch_id);
        } elseif ($selectedBranch) {
            $monthlyBase->where('branch_id', $selectedBranch);
        }
        
        if ($user->company?->auto_approve_visitors) {
            $monthlyBase->where('status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin') {
        if ($selectedCompany) {
            $monthlyBase->where('company_id', $selectedCompany);
            if ($selectedBranch) {
                $monthlyBase->where('branch_id', $selectedBranch);
            }
        }
    }

    $monthly = $monthlyBase
        ->selectRaw("DATE_FORMAT(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30'), '%b') as month, COUNT(*) as count")
        ->groupBy('month')
        ->orderByRaw("STR_TO_DATE(month, '%b')")
        ->get();

    $monthly = collect($monthly);
    $chartLabels = $monthly->pluck('month');
    $chartData   = $monthly->pluck('count');

    // ------------------------ Hourly chart ------------------------
    $singleDay = ($from === $to);
    $hourBase = Visitor::query()
        ->when(($user->role ?? null) === 'company', function($q) use ($user) {
            $q->where('company_id', $user->company_id);
            if (!empty($user->branch_id)) {
                $q->where('branch_id', $user->branch_id);
            }
            if ($user->company?->auto_approve_visitors) {
                $q->where('status', 'Approved');
            }
        })
        ->when(($user->role ?? null) === 'superadmin', function($q) use ($selectedCompany, $selectedBranch) {
            if ($selectedCompany) {
                $q->where('company_id', $selectedCompany);
                if ($selectedBranch) {
                    $q->where('branch_id', $selectedBranch);
                }
            }
        });

    if ($singleDay) {
        $hourBase->whereRaw("DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) = ?", [$from]);
    } else {
        // If multiple days, show today's hours to keep the chart meaningful
        $hourBase->whereRaw("DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) = CURDATE()");
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
        $hourData[]   = isset($hourly[$i]) ? (int)$hourly[$i]->count : 0;
    }

    // ------------------------ Day-wise chart (range) ------------------------
    $dayWise = Visitor::query()
        ->when(($user->role ?? null) === 'company', function($q) use ($user) {
            $q->where('company_id', $user->company_id);
            if (!empty($user->branch_id)) {
                $q->where('branch_id', $user->branch_id);
            }
            if ($user->company?->auto_approve_visitors) {
                $q->where('status', 'Approved');
            }
        })
        ->when(($user->role ?? null) === 'superadmin', function($q) use ($selectedCompany, $selectedBranch) {
            if ($selectedCompany) {
                $q->where('company_id', $selectedCompany);
                if ($selectedBranch) {
                    $q->where('branch_id', $selectedBranch);
                }
            }
        })
        ->whereRaw("DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) BETWEEN ? AND ?", [$from, $to])
        ->selectRaw("DATE(CONVERT_TZ(COALESCE(in_time, created_at), '+00:00', '+05:30')) as date, COUNT(*) as count")
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('count', 'date');

    $periodStart = Carbon::parse($from);
    $periodEnd   = Carbon::parse($to);
    $dayWiseLabels = [];
    $dayWiseData   = [];
    for ($d = $periodStart->copy(); $d->lte($periodEnd); $d->addDay()) {
        $key = $d->format('Y-m-d');
        $dayWiseLabels[] = $d->format('d M');
        $dayWiseData[]   = $dayWise[$key] ?? 0;
    }

    // ------------------------ Department-wise visitors (range) ------------------------
    $deptQuery = DB::table('visitors')
        ->join('departments', 'visitors.department_id', '=', 'departments.id')
        ->select('departments.name as department', DB::raw('COUNT(*) as total'))
        ->whereRaw("DATE(CONVERT_TZ(COALESCE(visitors.in_time, visitors.created_at), '+00:00', '+05:30')) BETWEEN ? AND ?", [$from, $to])
        ->groupBy('departments.name')
        ->orderBy('departments.name');

    if (($user->role ?? null) === 'company') {
        $deptQuery->where('visitors.company_id', $user->company_id);
        if (!empty($user->branch_id)) {
            $deptQuery->where('visitors.branch_id', $user->branch_id);
        } elseif ($selectedBranch) {
            $deptQuery->where('visitors.branch_id', $selectedBranch);
        }
        if ($user->company?->auto_approve_visitors) {
            $deptQuery->where('visitors.status', 'Approved');
        }
    } elseif (($user->role ?? null) === 'superadmin') {
        if ($selectedCompany) {
            $deptQuery->where('visitors.company_id', $selectedCompany);
            if ($selectedBranch) {
                $deptQuery->where('visitors.branch_id', $selectedBranch);
            }
        }
    }

    // Get department data
    $deptData = $deptQuery->get();
    $deptLabels = $deptData->pluck('department');
    $deptCounts = $deptData->pluck('total');
    $totalDeptVisitors = $deptCounts->sum();

    // ------------------------ All companies for superadmin filter ------------------------
    $companies = Company::all();

    return view('dashboard', [
        'autoApprove'       => $autoApprove,
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
        'companies'         => Company::orderBy('name')->get(),
        'selectedCompany'   => $selectedCompany,
        'selectedBranch'    => $selectedBranch,
        'from'              => $from,
        'to'                => $to,
    ]);
}

}
