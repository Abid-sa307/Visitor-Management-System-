<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisitorCategory;
use App\Models\Company;
use App\Models\Branch;

class VisitorCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first company and branch for testing
        $company = Company::first();
        $branch = Branch::first();

        if (!$company) {
            echo "No company found. Please run CompanySeeder first.\n";
            return;
        }

        // Create sample visitor categories
        $categories = [
            [
                'name' => 'Business Meeting',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
            ],
            [
                'name' => 'Interview',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
            ],
            [
                'name' => 'Delivery',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
            ],
            [
                'name' => 'Maintenance',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
            ],
            [
                'name' => 'Consultation',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
            ],
        ];

        foreach ($categories as $categoryData) {
            VisitorCategory::create($categoryData);
        }

        echo "Created " . count($categories) . " sample visitor categories.\n";
    }
}
