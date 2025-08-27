<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run(): void
{
    // ✅ First seed companies and other master data
    $this->call([
        CompanySeeder::class,
        DepartmentSeeder::class,
        VisitorCategorySeeder::class,
    ]);

    // ✅ Then seed the default users
    $this->call([
        UserSeeder::class,
        DefaultSeeder::class,
    ]);
}
    
}
