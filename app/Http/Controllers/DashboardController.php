<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Summary counts
        $approvedCount = Visitor::where('status', 'Approved')->count();
        $pendingCount = Visitor::where('status', 'Pending')->count();
        $rejectedCount = Visitor::where('status', 'Rejected')->count();

        // Latest Visitors
        $latestVisitors = Visitor::latest()->take(6)->get();

        // Monthly Chart
        $monthly = Visitor::selectRaw("DATE_FORMAT(created_at, '%b') as month, COUNT(*) as count")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderByRaw("STR_TO_DATE(month, '%b')")
            ->get()
            ->pluck('count', 'month');

        $chartLabels = $monthly->keys();
        $chartData = $monthly->values();

        // Hourly (Today)
        $hourly = Visitor::whereDate('created_at', today())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->pluck('count', 'hour');

        $hourLabels = [];
        $hourData = [];
        for ($i = 8; $i <= 18; $i++) {
            $hourLabels[] = $i . ':00';
            $hourData[] = $hourly[$i] ?? 0;
        }

        // Day-wise (Last 7 days)
        $dayWise = Visitor::where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $dayWiseLabels = [];
        $dayWiseData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayWiseLabels[] = Carbon::parse($date)->format('D');
            $dayWiseData[] = $dayWise[$date] ?? 0;
        }

        // Department-wise Visitors
        $deptData = DB::table('visitors')
            ->join('departments', 'visitors.department_id', '=', 'departments.id')
            ->select('departments.name as department', DB::raw('COUNT(*) as total'))
            ->groupBy('departments.name')
            ->orderBy('departments.name')
            ->get();

        $deptLabels = $deptData->pluck('department');
        $deptCounts = $deptData->pluck('total');

        // Date filter
        $selectedDate = $request->date ?? today()->toDateString();
        $visitorsByDate = Visitor::whereDate('created_at', $selectedDate)->get();

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
            'visitorsByDate'
        ));
    }
}
