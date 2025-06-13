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
        // First create the default user
        $this->call(UserSeeder::class);

        // Then seed all master tables
        $this->call([
            CompanySeeder::class,
            DepartmentSeeder::class,
            VisitorCategorySeeder::class,
            // Add other seeders here as you create them
        ]);

        // User::create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'admin',
        // ]);

        // User::create([
        //     'name' => 'Guard User',
        //     'email' => 'guard@gmail.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'guard',
        // ]);
    }
    
}
