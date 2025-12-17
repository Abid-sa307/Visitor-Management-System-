<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getByCompany(Company $company)
    {
        \Log::info('BranchController - getByCompany called', [
            'company_id' => $company->id,
            'branches_count' => $company->branches()->count()
        ]);
        
        return response()->json(
            $company->branches()
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray()
        );
    }
}
