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
        
        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
            
            // If user has specific departments assigned
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('department_id', auth()->user()->departments->pluck('id'));
            }
        }
        
        $visitors = $query->latest()->paginate(20);
        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);

        return view('visitors.report', compact('visitors', 'companies', 'departments') + $filters);
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
        
        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
            
            // If user has specific departments assigned
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('department_id', auth()->user()->departments->pluck('id'));
            }
        }

        $visits = $query->latest('in_time')->paginate(20);
        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);

        return view('visitors.visitor_inout', compact('visits', 'companies', 'departments') + $filters);
    }

    public function securityChecks(Request $request)
    {
        $query = SecurityCheck::with(['visitor' => function($q) {
            $q->with(['company', 'department', 'branch']);
        }, 'securityOfficer']);

        $filters = [];
        
        // Apply date range filter
        if ($request->filled('from')) {
            $query->whereDate('security_checks.created_at', '>=', $request->from);
            $filters['from'] = $request->from;
        }
        if ($request->filled('to')) {
            $query->whereDate('security_checks.created_at', '<=', $request->to);
            $filters['to'] = $request->to;
        }

    // Apply company filter
    if ($request->filled('company_id')) {
        $query->whereHas('visitor', function($q) use ($request) {
            $q->where('company_id', $request->company_id);
        });
        $filters['company_id'] = $request->company_id;
    }

    // Apply department filter
    if ($request->filled('department_id')) {
        $query->whereHas('visitor', function($q) use ($request) {
            $q->where('department_id', $request->department_id);
        });
        $filters['department_id'] = $request->department_id;
    }
    
    // Apply branch filter
    if ($request->filled('branch_id')) {
        $query->whereHas('visitor', function($q) use ($request) {
            $q->where('branch_id', $request->branch_id);
        });
        $filters['branch_id'] = $request->branch_id;
    }

    // Apply company filter for non-superadmins
    if (auth()->user()->role !== 'superadmin') {
        $query->whereHas('visitor', function($q) {
            $q->where('company_id', auth()->user()->company_id);
            
            // If user has specific departments assigned
            if (auth()->user()->departments->isNotEmpty()) {
                $q->whereIn('department_id', auth()->user()->departments->pluck('id'));
            }
        });
    }

    $securityChecks = $query->latest('security_checks.created_at')->paginate(20)->appends($request->query());
    $companies = $this->getCompanies();
    $departments = $this->getDepartments($request);
    
    // Get branches based on selected company
    $branches = [];
    if ($request->filled('company_id')) {
        $branches = \App\Models\Branch::where('company_id', $request->company_id)
            ->pluck('name', 'id')
            ->toArray();
    }
    
    return view('reports.security_checks', compact(
        'securityChecks', 
        'companies', 
        'departments',
        'branches'
    ) + $filters);
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
        
        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
            
            // If user has specific departments assigned
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('department_id', auth()->user()->departments->pluck('id'));
            }
        }

        $approvals = $query->latest('approved_at')->paginate(20);
        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);

        return view('visitors.approval_status', compact('approvals', 'companies', 'departments') + $filters);
    }

    // Hourly Report
    public function hourlyReport(Request $request)
    {
        $query = Visitor::whereNotNull('in_time')
            ->select(
                DB::raw('HOUR(in_time) as hour'),
                DB::raw('COUNT(*) as total_visits'),
                DB::raw('COUNT(CASE WHEN out_time IS NULL THEN 1 END) as current_visitors')
            );

        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
            
            // If user has specific departments assigned
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('department_id', auth()->user()->departments->pluck('id'));
            }
        }

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('in_time', $request->date);
        } else {
            $query->whereDate('in_time', now()->format('Y-m-d'));
        }

        // Apply company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $hourlyData = $query->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);
        
        return view('reports.hourly', [
            'hourlyData' => $hourlyData,
            'selectedDate' => $request->date ?? now()->format('Y-m-d'),
            'companies' => $companies,
            'departments' => $departments,
            'filters' => $request->all()
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

    // Helper Methods
    private function getCompanies()
    {
        if (auth()->user()->role === 'superadmin') {
            return Company::pluck('name', 'id')->mapWithKeys(function ($name, $id) {
                return [$id => $name];
            })->toArray();
        } else {
            return Company::where('id', auth()->user()->company_id)
                ->pluck('name', 'id')
                ->mapWithKeys(function ($name, $id) {
                    return [$id => $name];
                })
                ->toArray();
        }
    }

    private function getDepartments($request)
    {
        $query = Department::query();
        
        // For non-superadmins, filter by user's company
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
            
            // If user has specific departments assigned
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('id', auth()->user()->departments->pluck('id'));
            }
        } elseif ($request->filled('company_id')) {
            // For superadmins, filter by selected company if any
            $query->where('company_id', $request->company_id);
        }
        
        return $query->pluck('name', 'id')->toArray();
    }
}