<?php

namespace App\Http\Controllers;

use App\Models\SecurityCheck;
use App\Models\Visitor;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SecurityCheckExport;
use App\Exports\VisitorExport;
use App\Exports\VisitExport;
use App\Exports\ApprovalExport;
use App\Exports\HourlyReportExport;

class ReportController extends Controller
{
    // Visitor Reports
    public function visitors(Request $request)
    {
        $query = Visitor::with(['company', 'department']);

        // Apply filters
        $filters = $this->applyCommonFilters($query, $request);
        
        $visitors = $query->latest()->paginate(20);
        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);

        return view('reports.visitors', compact('visitors', 'companies', 'departments') + $filters);
    }

    // Visit Reports (In/Out)
    public function visits(Request $request)
    {
        $query = Visitor::whereNotNull('in_time')->with(['company', 'department']);
        
        // Apply filters
        $filters = $this->applyCommonFilters($query, $request);
        
        // Additional visit-specific filters
        if ($request->filled('visit_type')) {
            $query->where('purpose', $request->visit_type);
        }

        $visits = $query->latest('in_time')->paginate(20);
        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);

        return view('reports.visits', compact('visits', 'companies', 'departments') + $filters);
    }

    // Security Checks Report
    public function securityChecks(Request $request)
    {
        $query = SecurityCheck::with(['visitor' => function($q) {
            $q->with(['company', 'department']);
        }]);

        // Apply date filters
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->whereHas('visitor', function($q) {
                $q->where('company_id', auth()->user()->company_id);
            });
        }

        $securityChecks = $query->latest()->paginate(20);
        $companies = $this->getCompanies();
        
        return view('reports.security_checks', compact('securityChecks', 'companies') + [
            'from' => $request->from,
            'to' => $request->to
        ]);
    }

    // Approvals Report
    public function approvals(Request $request)
    {
        $query = Visitor::whereNotNull('approved_at')
            ->with(['company', 'department', 'approvedBy']);
        
        // Apply filters
        $filters = $this->applyCommonFilters($query, $request);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $approvals = $query->latest('approved_at')->paginate(20);
        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);

        return view('reports.approvals', compact('approvals', 'companies', 'departments') + $filters);
    }

    // Hourly Report
    public function hourlyReport(Request $request)
    {
        $query = Visitor::whereNotNull('in_time')
            ->select(
                DB::raw('HOUR(in_time) as hour'),
                DB::raw('COUNT(*) as total_visits'),
                DB::raw('COUNT(CASE WHEN out_time IS NULL THEN 1 END) as current_visitors')
            )
            ->groupBy('hour')
            ->orderBy('hour');

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('in_time', $request->date);
        }

        $hourlyData = $query->get();
        
        return view('reports.hourly', [
            'hourlyData' => $hourlyData,
            'selectedDate' => $request->date ?? now()->format('Y-m-d')
        ]);
    }

    // Export Methods
    public function exportVisitors(Request $request)
    {
        return Excel::download(new VisitorExport($request), 'visitors_'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportVisits(Request $request)
    {
        return Excel::download(new VisitExport($request), 'visits_'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportSecurityChecks(Request $request)
    {
        return Excel::download(new SecurityCheckExport($request), 'security_checks_'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportApprovals(Request $request)
    {
        return Excel::download(new ApprovalExport($request), 'approvals_'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportHourlyReport(Request $request)
    {
        return Excel::download(new HourlyReportExport($request), 'hourly_report_'.now()->format('Y-m-d').'.xlsx');
    }

    // Helper Methods
    private function applyCommonFilters($query, $request)
    {
        $filters = [];
        
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
            $filters['from'] = $request->from;
        }
        
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
            $filters['to'] = $request->to;
        }
        
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
            $filters['company_id'] = $request->company_id;
        }
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
            $filters['department_id'] = $request->department_id;
        }
        
        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
        }
        
        return $filters;
    }
    
    private function getCompanies()
    {
        return auth()->user()->role === 'superadmin' 
            ? Company::orderBy('name')->pluck('name', 'id')
            : collect([]);
    }
    
    private function getDepartments($request)
    {
        if ($request->filled('company_id')) {
            return Department::where('company_id', $request->company_id)
                ->orderBy('name')
                ->pluck('name', 'id');
        }
        
        return collect([]);
    }
}
