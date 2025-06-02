<?php

namespace Database\Seeders;

use App\Models\VisitorCategory;
use App\Models\Company;
use Illuminate\Database\Seeder;

class VisitorCategorySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        VisitorCategory::create(['company_id' => $company->id, 'name' => 'Guest']);
        VisitorCategory::create(['company_id' => $company->id, 'name' => 'Vendor']);
        VisitorCategory::create(['company_id' => $company->id, 'name' => 'Parent']);
    }
}