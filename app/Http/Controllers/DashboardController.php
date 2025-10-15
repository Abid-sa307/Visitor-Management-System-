<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    
    $user = auth()->user();  // Get the authenticated user first

$autoApprove = false;
if ($user->role === 'company' && $user->company) {
    $autoApprove = (bool) $user->company->auto_approve;
}

$selectedCompany = $request->company_id ?? null;
$selectedDate = $request->date ?? today()->toDateString();


    // ------------------------ Base visitor query ------------------------
    $visitorQuery = Visitor::query()->whereDate('created_at', $selectedDate);

    if ($user->role === 'company') {
        $visitorQuery->where('company_id', $user->company_id);
        if ($user->company?->auto_approve) {
            $visitorQuery->where('status', 'Approved');
        }
    } elseif ($user->role === 'superadmin' && $selectedCompany) {
        $visitorQuery->where('company_id', $selectedCompany);
    }

    // ------------------------ Summary counts ------------------------
    $approvedCount = (clone $visitorQuery)->where('status', 'Approved')->count();
    $pendingCount  = (clone $visitorQuery)->where('status', 'Pending')->count();
    $rejectedCount = (clone $visitorQuery)->where('status', 'Rejected')->count();

    // ------------------------ Latest visitors ------------------------
    $latestVisitors = (clone $visitorQuery)->latest()->take(6)->get();

    // ------------------------ Monthly chart (current year) ------------------------
    $monthly = (clone $visitorQuery)
        ->whereYear('created_at', now()->year)
        ->selectRaw("DATE_FORMAT(created_at, '%b') as month, COUNT(*) as count")
        ->groupBy('month')
        ->orderByRaw("STR_TO_DATE(month, '%b')")
        ->get();

    $monthly = collect($monthly);
    $chartLabels = $monthly->pluck('month');
    $chartData   = $monthly->pluck('count');

    // ------------------------ Hourly chart (today) ------------------------
    $hourly = (clone $visitorQuery)
        ->whereDate('created_at', Carbon::today())
        ->selectRaw('HOUR(CONVERT_TZ(created_at, "+00:00", "+05:30")) as hour, COUNT(*) as count')
        ->groupBy('hour')
        ->get();

    $hourly = collect($hourly)->pluck('count', 'hour');

    $hourLabels = [];
    $hourData   = [];
    for ($i = 8; $i <= 18; $i++) {
        $hourLabels[] = $i . ':00';
        $hourData[]   = $hourly[$i] ?? 0;
    }

    // ------------------------ Day-wise chart (last 7 days) ------------------------
    $dayWise = Visitor::query()
        ->when($user->role === 'company', fn($q) => $q->where('company_id', $user->company_id))
        ->when($user->role === 'company' && $user->company?->auto_approve, fn($q) => $q->where('status', 'Approved'))
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $dayWise = collect($dayWise)->pluck('count', 'date');

    $dayWiseLabels = [];
    $dayWiseData   = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $dayWiseLabels[] = Carbon::parse($date)->format('D');
        $dayWiseData[]   = $dayWise[$date] ?? 0;
    }

    // ------------------------ Department-wise visitors ------------------------
    $deptQuery = DB::table('visitors')
        ->join('departments', 'visitors.department_id', '=', 'departments.id')
        ->select('departments.name as department', DB::raw('COUNT(*) as total'))
        ->whereDate('visitors.created_at', $selectedDate)
        ->groupBy('departments.name')
        ->orderBy('departments.name');

    if ($user->role === 'company') {
        $deptQuery->where('visitors.company_id', $user->company_id);
        if ($user->company?->auto_approve) {
            $deptQuery->where('visitors.status', 'Approved');
        }
    } elseif ($selectedCompany) {
        $deptQuery->where('visitors.company_id', $selectedCompany);
    }

    $deptData   = collect($deptQuery->get());
    $deptLabels = $deptData->pluck('department');
    $deptCounts = $deptData->pluck('total');
    $totalDeptVisitors = $deptCounts->sum();

    // ------------------------ Visitors by selected date ------------------------
    $visitorsByDate = (clone $visitorQuery)->get();

    // ------------------------ All companies for superadmin filter ------------------------
    $companies = Company::all();

    return view('dashboard', compact(
        'approvedCount',
        'pendingCount',
        'rejectedCount',
        'latestVisitors',
        'chartLabels',
        'chartData',
        'hourLabels',
        'hourData',
        'dayWiseLabels',
        'dayWiseData',
        'deptLabels',
        'deptCounts',
        'totalDeptVisitors',
        'visitorsByDate',
        'companies',
        'selectedCompany',
        'selectedDate',
        'autoApprove',
    ));
}

}
