<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Approval;
use App\Models\Department;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Display a listing of approvals with filters.
     */
    public function index(Request $request)
    {
        // Detect which guard/user is logged in
        $isCompanyGuard = Auth::guard('company')->check();
        $user = $isCompanyGuard ? Auth::guard('company')->user() : Auth::user();

        /**
         * 🔹 SUPER ADMIN DETECTION
         * IMPORTANT: Use the SAME logic you use in EmployeesController.
         * Example 1:  $isSuper = !$isCompanyGuard && ($user->is_super ?? false);
         * Example 2:  $isSuper = !$isCompanyGuard && $user->role === 'super_admin';
         *
         * Replace the line below with the correct one from your Employees page.
         */
        $isSuper = !$isCompanyGuard && ($user->is_super ?? false);

        // Base query
        $query = Visitor::with(['department', 'branch', 'category', 'company']);

        // 🔹 COMPANY FILTER
        $selectedCompanyId = null;

        if ($isSuper) {
            // Super admin can filter by any company
            if ($request->filled('company_id')) {
                $selectedCompanyId = (int) $request->input('company_id');
                $query->where('company_id', $selectedCompanyId);
            }
            // If no company_id, show all companies (no where company_id)
        } else {
            // Company user – always restricted to own company
            $selectedCompanyId = $user->company_id;
            $query->where('company_id', $selectedCompanyId);
        }

        // 🔹 DATE RANGE FILTER (same as employees)
        $from = $request->input('from');
        $to   = $request->input('to');

        // If your visitor date is something else (e.g. visit_date), change created_at accordingly
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        } else {
            $query->whereDate('created_at', '>=', now()->subDays(30)->toDateString());
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        // 🔹 STATUS FILTER
        $status = $request->input('status');
        if ($status && in_array($status, ['Pending', 'Approved', 'Rejected', 'Completed'])) {
            $query->where('status', $status);
        } else {
            // Default: everything except Completed
            $query->where('status', '!=', 'Completed');
        }

        // 🔹 BRANCH FILTER
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        // 🔹 DEPARTMENT FILTER
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        // 🔹 DROPDOWN DATA

        $companies   = [];
        $branches    = [];
        $departments = [];

        if ($isSuper) {
            // Super admin can see all companies
            $companies = Company::pluck('name', 'id')->toArray();

            if ($selectedCompanyId) {
                $branches = Branch::where('company_id', $selectedCompanyId)
                    ->pluck('name', 'id')
                    ->toArray();

                $departments = Department::where('company_id', $selectedCompanyId)
                    ->pluck('name', 'id')
                    ->toArray();
            }
        } else {
            // Company user – only their assigned branches & departments
            // Get user's assigned branch IDs from the pivot table
            $userBranchIds = $user->branches()->pluck('branches.id')->toArray();
            
            if (!empty($userBranchIds)) {
                // Filter branches by user's assigned branches
                $branches = Branch::whereIn('id', $userBranchIds)
                    ->pluck('name', 'id')
                    ->toArray();

                // Get user's assigned department IDs from the pivot table
                $userDepartmentIds = $user->departments()->pluck('departments.id')->toArray();
                
                if (!empty($userDepartmentIds)) {
                    // Filter departments by user's assigned departments
                    $departments = Department::whereIn('id', $userDepartmentIds)
                        ->where('company_id', $user->company_id)
                        ->pluck('name', 'id')
                        ->toArray();
                } else {
                    // Fallback: filter departments by user's assigned branches
                    $departments = Department::whereIn('branch_id', $userBranchIds)
                        ->where('company_id', $user->company_id)
                        ->pluck('name', 'id')
                        ->toArray();
                }
            } else {
                // Fallback to single branch if user has branch_id set
                if ($user->branch_id) {
                    $branches = Branch::where('id', $user->branch_id)
                        ->pluck('name', 'id')
                        ->toArray();
                    $departments = Department::where('branch_id', $user->branch_id)
                        ->where('company_id', $user->company_id)
                        ->pluck('name', 'id')
                        ->toArray();
                } else {
                    // If no branches assigned, get all company branches/departments
                    $branches = Branch::where('company_id', $user->company_id)
                        ->pluck('name', 'id')
                        ->toArray();
                    $departments = Department::where('company_id', $user->company_id)
                        ->pluck('name', 'id')
                        ->toArray();
                }
            }
        }

        $visitors = $query->latest()->paginate(10)->withQueryString();

        // Add flags used by Blade
        $visitors->getCollection()->each(function ($visitor) {
            $visitor->can_undo_status  = in_array($visitor->status, ['Approved', 'Rejected']);
            $visitor->status_changed_at = $visitor->updated_at;
        });

        return view('visitors.approvals', compact(
            'visitors',
            'departments',
            'branches',
            'companies',
            'isSuper'
        ));
    }

    // other methods...
}
