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

    User::firstOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'company_id' => $company->id, // ✅ now it's guaranteed to exist
            'role' => 'superadmin',
        ]
    );
}
}
