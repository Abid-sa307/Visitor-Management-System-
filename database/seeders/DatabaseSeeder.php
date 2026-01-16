<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run(): void
{
    // ✅ First create roles and permissions
    $this->call([
        RoleAndPermissionSeeder::class,
    ]);

    // ✅ Then seed companies and other master data
    $this->call([
        CompanySeeder::class,
        DepartmentSeeder::class,
    ]);

    // ✅ Finally seed the default users
    $this->call([
        UserSeeder::class,
        DefaultSeeder::class,
    ]);
}
    
}
