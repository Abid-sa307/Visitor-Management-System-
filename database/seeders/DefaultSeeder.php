<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DefaultSeeder extends Seeder
{
    public function run(): void
    {
        Company::updateOrCreate(
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
    }
}
