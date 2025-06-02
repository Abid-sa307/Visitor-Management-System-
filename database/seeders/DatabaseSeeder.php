<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
    }
}
