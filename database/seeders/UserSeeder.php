<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Company;

class UserSeeder extends Seeder
{
    
public function run(): void
{
    $company = Company::firstOrCreate(
        ['email' => 'contact@abcindustries.com'],
        [
            'name' => 'ABC Industries',
            'address' => '123 Street', // ✅ required!
            'contact_number' => '1234567890',
            'gst_number' => 'GST123ABC',
            'website' => 'abc.com',
            'notification_settings' => json_encode([
                'email' => true,
                'sms' => true,
                'whatsapp' => false
            ]),
        ]
    );

        // Create or update super admin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'visitormanagmentsystemsoftware@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('NNT!rM@9Q2^Sx#P4D7'),
                'company_id' => $company->id,
                'role' => 'superadmin',
                'is_super_admin' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        // Ensure the user has the correct role and permissions
        $superAdmin->assignRole('superadmin');
}
}
