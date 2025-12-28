<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getByCompany(Company $company)
    {
        return response()->json(
            $company->branches()
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }

    /**
     * Get departments for a specific branch
     */
    public function getDepartments(Branch $branch)
    {
        try {
            $departments = $branch->departments()
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($departments);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load departments',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
