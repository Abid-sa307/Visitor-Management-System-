<?php

namespace App\Http\Controllers;

use App\Models\SecurityCheck;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SecurityCheckExport;

class ReportController extends Controller
{
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

        return view('reports.security_checks', compact('securityChecks'));
    }

    public function exportSecurityChecks(Request $request)
    {
        return Excel::download(new SecurityCheckExport($request), 'security_checks_'.now()->format('Y-m-d').'.xlsx');
    }
}
