<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Department;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first company and branch for testing
        $company = Company::first();
        $branch = Branch::first();
        $department = Department::first();

        if (!$company) {
            echo "No company found. Please run CompanySeeder first.\n";
            return;
        }

        // Create sample employees
        $employees = [
            [
                'name' => 'John Smith',
                'designation' => 'Manager',
                'email' => 'john.smith@example.com',
                'phone' => '1234567890',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
                'department_id' => $department ? $department->id : null,
            ],
            [
                'name' => 'Jane Doe',
                'designation' => 'Team Lead',
                'email' => 'jane.doe@example.com',
                'phone' => '0987654321',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
                'department_id' => $department ? $department->id : null,
            ],
            [
                'name' => 'Mike Johnson',
                'designation' => 'Developer',
                'email' => 'mike.johnson@example.com',
                'phone' => '5551234567',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
                'department_id' => $department ? $department->id : null,
            ],
            [
                'name' => 'Sarah Wilson',
                'designation' => 'HR Manager',
                'email' => 'sarah.wilson@example.com',
                'phone' => '5559876543',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
                'department_id' => $department ? $department->id : null,
            ],
            [
                'name' => 'David Brown',
                'designation' => 'Sales Executive',
                'email' => 'david.brown@example.com',
                'phone' => '5552468135',
                'company_id' => $company->id,
                'branch_id' => $branch ? $branch->id : null,
                'department_id' => $department ? $department->id : null,
            ],
        ];

        foreach ($employees as $employeeData) {
            Employee::create($employeeData);
        }

        echo "Created " . count($employees) . " sample employees.\n";
    }
}
