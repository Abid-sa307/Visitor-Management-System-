<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create([
            'name' => 'ABC Industries',
            'address' => '123 Main Street, New York, USA',
            'contact_number' => '+1 234 567 890',
            'logo' => null,
            'gst_number' => 'ABC123XYZ',
            'website' => 'www.abcindustries.com',
            'email' => 'contact@abcindustries.com',
            'notification_settings' => json_encode([
                'email' => true,
                'sms' => false,
                'whatsapp' => false
            ]),
        ]);
    }
}
