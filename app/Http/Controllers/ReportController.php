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
        $branches = $this->getBranches($request);

        return view('visitors.report', compact('visitors', 'companies', 'departments', 'branches') + $filters);
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
        }]);

        $filters = [];
        
        $from = $request->input('from') ?: now()->format('Y-m-d');
        $to = $request->input('to') ?: now()->format('Y-m-d');
        
        $query->whereDate('security_checks.created_at', '>=', $from)
              ->whereDate('security_checks.created_at', '<=', $to);
              
        $filters['from'] = $from;
        $filters['to'] = $to;

    // Apply company filter
    if ($request->filled('company_id')) {
        $query->whereHas('visitor', function($q) use ($request) {
            $q->where('company_id', $request->company_id);
        });
        $filters['company_id'] = $request->company_id;
    }

    // Apply department filter
    if ($request->filled('department_id')) {
        $departmentIds = is_array($request->department_id) ? $request->department_id : [$request->department_id];
        $query->whereHas('visitor', function($q) use ($departmentIds) {
            $q->whereIn('department_id', $departmentIds);
        });
        $filters['department_id'] = $request->department_id;
    }
    
    // Apply branch filter
    if ($request->filled('branch_id')) {
        $branchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
        $query->whereHas('visitor', function($q) use ($branchIds) {
            $q->whereIn('branch_id', $branchIds);
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
    $branches = $this->getBranches($request);
    
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
            ->with(['company', 'department', 'approvedBy', 'rejectedBy']);
        
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

        $visitors = $query->latest('approved_at')->paginate(20);
        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);
        $branches = $this->getBranches($request);

        return view('visitors.approval_status', compact('visitors', 'companies', 'departments', 'branches') + $filters);
    }

    // Hourly Report
    public function hourlyReport(Request $request)
    {
        $from = $request->input('from') ?: now()->format('Y-m-d');
        $to = $request->input('to') ?: now()->format('Y-m-d');

        $query = Visitor::whereNotNull('in_time')
            ->leftJoin('branches', 'visitors.branch_id', '=', 'branches.id')
            ->leftJoin('departments', 'visitors.department_id', '=', 'departments.id')
            ->select(
                DB::raw('DATE_FORMAT(in_time, "%Y-%m-%d %H:00:00") as hour'),
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(NULLIF(TRIM(branches.name), ""), "Unknown Branch") as branch_name'),
                DB::raw('COALESCE(NULLIF(TRIM(departments.name), ""), "Unknown Department") as department_name')
            );

        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->where('visitors.company_id', auth()->user()->company_id);
            
            // If user has specific departments assigned
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('visitors.department_id', auth()->user()->departments->pluck('id'));
            }
        }

        // Apply date filter range
        $query->whereDate('in_time', '>=', $from)
              ->whereDate('in_time', '<=', $to);

        // Apply company filter
        if ($request->filled('company_id')) {
            $query->where('visitors.company_id', $request->company_id);
        }

        // Apply branch filter
        if ($request->filled('branch_id')) {
            $branchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
            $query->whereIn('visitors.branch_id', $branchIds);
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $departmentIds = is_array($request->department_id) ? $request->department_id : [$request->department_id];
            $query->whereIn('visitors.department_id', $departmentIds);
        }

        $series = $query->groupBy('hour', 'branch_name', 'department_name')
            ->orderBy('hour')
            ->get()
            ->toArray();

        $companies = $this->getCompanies();
        $departments = $this->getDepartments($request);
        $branches = $this->getBranches($request);
        
        return view('visitors.reports_hourly', [
            'series' => $series,
            'from' => $from,
            'to' => $to,
            'companies' => $companies,
            'departments' => $departments,
            'branches' => $branches,
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
        
        $from = $request->input('from') ?: now()->format('Y-m-d');
        $to = $request->input('to') ?: now()->format('Y-m-d');
        
        $query->whereDate('created_at', '>=', $from)
              ->whereDate('created_at', '<=', $to);
              
        $filters['from'] = $from;
        $filters['to'] = $to;
        
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
            $filters['company_id'] = $request->company_id;
        }
        
        if ($request->filled('branch_id')) {
            $branchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
            $query->whereIn('branch_id', $branchIds);
            $filters['branch_id'] = $request->branch_id;
        }
        
        if ($request->filled('department_id')) {
            $departmentIds = is_array($request->department_id) ? $request->department_id : [$request->department_id];
            $query->whereIn('department_id', $departmentIds);
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
            return Company::orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            return Company::where('id', auth()->user()->company_id)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }
    }

    private function getDepartments($request)
    {
        $query = Department::select('id', 'name', 'branch_id');
        
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
        // If superadmin and no company selected, return all departments
        
        return $query->get()->keyBy('id')->toArray();
    }

    private function getBranches($request)
    {
        $query = \App\Models\Branch::query();
        
        // For non-superadmins, filter by user's assigned branches
        if (auth()->user()->role !== 'superadmin') {
            $user = auth()->user();
            // Get user's assigned branch IDs from the pivot table
            $userBranchIds = $user->branches()->pluck('branches.id')->toArray();
            
            if (!empty($userBranchIds)) {
                $query->whereIn('id', $userBranchIds);
            } else {
                // Fallback to single branch if user has branch_id set
                if ($user->branch_id) {
                    $query->where('id', $user->branch_id);
                } else {
                    // If no branches assigned, filter by company
                    $query->where('company_id', $user->company_id);
                }
            }
        } elseif ($request->filled('company_id')) {
            // For superadmins, filter by selected company if any
            $query->where('company_id', $request->company_id);
        }
        
        return $query->pluck('name', 'id')->toArray();
    }
}