<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        Department::create([
            'company_id' => $company->id,
            'name' => 'Human Resources',
        ]);

        Department::create([
            'company_id' => $company->id,
            'name' => 'Sales',
        ]);
    }
}