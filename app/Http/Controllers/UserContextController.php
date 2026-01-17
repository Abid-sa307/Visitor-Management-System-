<?php

namespace App\Http\Controllers;

use App\Support\UserContextResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserContextController extends Controller
{
    /**
     * Update the active branch or department context for the authenticated user.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('company')->user() ?? Auth::user();

        if (!$user) {
            abort(403);
        }

        $data = $request->validate([
            'type' => ['required', 'in:branch,department'],
            'value' => ['nullable', 'integer'],
        ]);

        if ($data['type'] === 'branch') {
            $this->updateBranchContext($user, $data['value']);
        } else {
            $this->updateDepartmentContext($user, $data['value']);
        }

        return back();
    }

    private function updateBranchContext($user, ?int $branchId): void
    {
        $branches = UserContextResolver::resolveBranches($user);

        if ($branches->isEmpty()) {
            Session::forget('active_branch_id');
            return;
        }

        if ($branchId === null) {
            Session::forget('active_branch_id');
        } else {
            if (!$branches->contains('id', $branchId)) {
                abort(403);
            }

            Session::put('active_branch_id', $branchId);
        }

        // Reset department if it's no longer valid for the new branch selection
        $departments = UserContextResolver::resolveDepartments($user, $branches);
        $activeDept = Session::get('active_department_id');

        if ($activeDept && !$departments->contains('id', $activeDept)) {
            Session::forget('active_department_id');
        }
    }

    private function updateDepartmentContext($user, ?int $departmentId): void
    {
        $branches = UserContextResolver::resolveBranches($user);
        $departments = UserContextResolver::resolveDepartments($user, $branches);

        if ($departments->isEmpty()) {
            Session::forget('active_department_id');
            return;
        }

        if ($departmentId === null) {
            Session::forget('active_department_id');
            return;
        }

        if (!$departments->contains('id', $departmentId)) {
            abort(403);
        }

        Session::put('active_department_id', $departmentId);
    }
}
