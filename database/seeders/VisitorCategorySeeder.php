<?php

namespace Database\Seeders;

use App\Models\VisitorCategory;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitorCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Truncate the table first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        VisitorCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all companies or create one if none exists
        $companies = Company::all();
        
        if ($companies->isEmpty()) {
            $company = Company::create([
                'name' => 'Default Company',
                'email' => 'company@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St, City, Country',
                'is_active' => true,
            ]);
            $companies = collect([$company]);
        }

        $categories = [
            [
                'name' => 'Guest',
                'description' => 'General visitors and guests',
                'is_active' => true
            ],
            [
                'name' => 'Vendor',
                'description' => 'Vendors and suppliers',
                'is_active' => true
            ],
            [
                'name' => 'Contractor',
                'description' => 'External contractors and service providers',
                'is_active' => true
            ],
            [
                'name' => 'Client',
                'description' => 'Business clients and partners',
                'is_active' => true
            ],
            [
                'name' => 'Interview',
                'description' => 'Job candidates',
                'is_active' => true
            ],
            [
                'name' => 'Delivery',
                'description' => 'Courier and delivery personnel',
                'is_active' => true
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Facility maintenance staff',
                'is_active' => true
            ],
            [
                'name' => 'Inactive Category',
                'description' => 'Inactive category for testing',
                'is_active' => false
            ],
        ];

        foreach ($companies as $company) {
            foreach ($categories as $category) {
                VisitorCategory::create([
                    'company_id' => $company->id,
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => $category['is_active'],
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 29)),
                ]);
            }
        }
    }
}