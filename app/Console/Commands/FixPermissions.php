<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixPermissions extends Command
{
    protected $signature = 'permissions:fix';
    protected $description = 'Fix permissions for the application';

    public function handle()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view visitor categories',
            'create visitor categories',
            'edit visitor categories',
            'delete visitor categories',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $companyRole = Role::firstOrCreate(['name' => 'company']);
        $companyRole->givePermissionTo([
            'view visitor categories',
            'create visitor categories',
            'edit visitor categories',
        ]);

        // Assign superadmin role to the first user (usually the admin)
        $user = User::first();
        if ($user && !$user->hasRole('superadmin')) {
            $user->assignRole('superadmin');
            $this->info('Assigned superadmin role to user: ' . $user->email);
        }

        $this->info('Permissions and roles have been set up successfully!');
    }
}