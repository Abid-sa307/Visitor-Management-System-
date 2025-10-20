<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\CompanyUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DefaultSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::updateOrCreate(
            ['name' => 'ABC Industries'],
            [
                'address' => 'Ahmedabad, Gujarat, India', // ✅ REQUIRED
                'email' => 'info@abcindustries.com',
                'contact_number' => '1234567890',
                'gst_number' => 'GSTABC123XYZ',
                'website' => 'https://abcindustries.com',
                'notification_settings' => json_encode([
                    'email' => true,
                    'sms' => false,
                    'whatsapp' => false
                ]),
            ]
        );

        // Ensure a default company user exists for login testing
        CompanyUser::updateOrCreate(
            ['email' => 'company@example.com'],
            [
                'name' => 'Company Admin',
                'password' => 'Password123!', // auto-hashed by casts in CompanyUser
                'company_id' => $company->id,
                'role' => 'company',
            ]
        );
    }
}
