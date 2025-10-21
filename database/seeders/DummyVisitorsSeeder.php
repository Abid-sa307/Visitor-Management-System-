<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Visitor;
use App\Models\Company;
use App\Models\Department;
use App\Models\Branch;

class DummyVisitorsSeeder extends Seeder
{
    /**
     * Seed a batch of realistic visitor records across the last 7 days
     * for available companies and their departments.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Pick companies to seed for (seed for all if small; else first few)
        $companies = Company::query()->get();
        if ($companies->isEmpty()) {
            // Create a minimal company if none exist
            $companies = collect([
                Company::create([
                    'name' => 'Sample Co.',
                    'address' => 'Sample Address',
                    'contact_number' => '0000000000',
                    'email' => 'sample@example.com',
                ])
            ]);
        }

        foreach ($companies as $company) {
            $departments = Department::where('company_id', $company->id)->pluck('id')->all();
            // Ensure branches exist: create two demo branches if none
            $branches = Branch::where('company_id', $company->id)->pluck('id')->all();
            if (empty($branches)) {
                $b1 = Branch::create([
                    'company_id' => $company->id,
                    'name' => 'Head Office',
                    'address' => $company->address,
                    'phone' => $company->contact_number,
                    'email' => $company->email,
                ]);
                $b2 = Branch::create([
                    'company_id' => $company->id,
                    'name' => 'Branch A',
                    'address' => 'Branch A Address',
                    'phone' => '0111222333',
                    'email' => 'branch-a@'.$company->id.'.example.com',
                ]);
                $branches = [$b1->id, $b2->id];
            }

            // 35 visitors per company (5 per day for 7 days)
            for ($d = 0; $d < 7; $d++) {
                $date = Carbon::now()->subDays($d);

                for ($i = 0; $i < 5; $i++) {
                    $name = $faker->name();
                    $phone = $faker->numerify('9#########');
                    $purpose = $faker->randomElement(['Meeting', 'Interview', 'Delivery', 'Maintenance', 'Other']);
                    $status = $faker->randomElement(['Pending','Approved','Rejected','Completed']);

                    // Ensure reasonable in/out times based on status
                    $createdAt = $date->copy()->setTime(rand(9, 17), rand(0, 59));
                    $inTime = null; $outTime = null;
                    if (in_array($status, ['Approved','Completed'], true)) {
                        $inTime = (clone $createdAt)->addMinutes(rand(5, 90));
                    }
                    if ($status === 'Completed') {
                        $outTime = (clone $inTime)->addMinutes(rand(15, 120));
                    }

                    Visitor::create([
                        'company_id'     => $company->id,
                        'branch_id'      => $faker->randomElement($branches),
                        'department_id'  => !empty($departments) ? $faker->randomElement($departments) : null,
                        'name'           => $name,
                        'email'          => $faker->safeEmail(),
                        'phone'          => $phone,
                        'purpose'        => $purpose,
                        'person_to_visit'=> $faker->name(),
                        'status'         => $status,
                        'in_time'        => $inTime,
                        'out_time'       => $outTime,
                        'created_at'     => $createdAt,
                        'updated_at'     => $createdAt,
                    ]);
                }
            }
        }
    }
}
