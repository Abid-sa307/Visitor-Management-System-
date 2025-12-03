<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateSuperAdmin extends Command
{
    protected $signature = 'user:create-superadmin';
    protected $description = 'Create a new superadmin user with default credentials';

    public function handle()
    {
        $email = 'visitormanagmentsystemsoftware@gmail.com';
        $password = 'Nnt@12345';
        
        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error('A user with this email already exists!');
            return 1;
        }

        // Create the user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'is_super_admin' => true,
            'role' => 'superadmin',
            'email_verified_at' => now(),
        ]);

        // Ensure the role exists
        if (!Role::where('name', 'superadmin')->exists()) {
            Role::create(['name' => 'superadmin']);
        }

        // Assign the role
        $user->assignRole('superadmin');

        $this->info('Superadmin user created successfully!');
        $this->line('Email: ' . $email);
        $this->line('Password: ' . $password);
        $this->newLine();
        $this->warn('Please change the password after first login!');

        return 0;
    }
}
