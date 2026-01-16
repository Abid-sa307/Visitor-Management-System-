<?php

namespace App\Support;

use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Collection;

class UserContextResolver
{
    /**
     * Resolve accessible branches for the given user.
     */
    public static function resolveBranches($user): Collection
    {
        if (!$user) {
            return collect();
        }

        if ($user instanceof User) {
            $branchIds = $user->branches()->pluck('branches.id')->filter()->unique();

            if ($branchIds->isNotEmpty()) {
                return Branch::whereIn('id', $branchIds->all())
                    ->orderBy('name')
                    ->get(['id', 'name']);
            }

            if (!empty($user->branch_id)) {
                return Branch::where('id', $user->branch_id)
                    ->orderBy('name')
                    ->get(['id', 'name']);
            }

            if (!empty($user->company_id)) {
                return Branch::where('company_id', $user->company_id)
                    ->orderBy('name')
                    ->get(['id', 'name']);
            }

            return collect();
        }

        $companyId = $user->company_id ?? null;
        $branchId = $user->branch_id ?? null;

        if ($branchId) {
            return Branch::where('id', $branchId)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        if ($companyId) {
            return Branch::where('company_id', $companyId)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return collect();
    }

    /**
     * Resolve accessible departments for the given user.
     */
    public static function resolveDepartments($user, ?Collection $branches = null): Collection
    {
        if (!$user) {
            return collect();
        }

        $branchIds = collect();

        if ($branches && $branches->isNotEmpty()) {
            $branchIds = $branches->pluck('id')->filter()->unique();
        }

        if ($user instanceof User) {
            $departmentIds = $user->departments()->pluck('departments.id')->filter()->unique();

            if ($departmentIds->isNotEmpty()) {
                return Department::whereIn('id', $departmentIds->all())
                    ->orderBy('name')
                    ->get(['id', 'name', 'branch_id']);
            }

            if ($branchIds->isEmpty() && !empty($user->branch_id)) {
                $branchIds = collect([$user->branch_id]);
            }
        } else {
            if ($branchIds->isEmpty() && !empty($user->branch_id)) {
                $branchIds = collect([$user->branch_id]);
            }
        }

        $companyId = $user->company_id ?? null;

        if ($branchIds->isNotEmpty()) {
            $query = Department::whereIn('branch_id', $branchIds->all());

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            return $query->orderBy('name')->get(['id', 'name', 'branch_id']);
        }

        if ($companyId) {
            return Department::where('company_id', $companyId)
                ->orderBy('name')
                ->get(['id', 'name', 'branch_id']);
        }

        return collect();
    }
}
