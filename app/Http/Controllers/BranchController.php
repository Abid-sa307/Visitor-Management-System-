<?php

namespace App\Http\Controllers;

use App\Models\Company;
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
}
