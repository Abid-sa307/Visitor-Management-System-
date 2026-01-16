<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'manage users',
            'manage companies',
            'manage visitors',
            'manage departments',
            'manage branches',
            'manage security',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $roles = [
            'superadmin' => [
                'manage users',
                'manage companies', 
                'manage visitors',
                'manage departments',
                'manage branches',
                'manage security',
                'view reports',
            ],
            'admin' => [
                'manage visitors',
                'manage departments',
                'manage branches',
                'manage security',
                'view reports',
            ],
            'company' => [
                'manage visitors',
                'manage departments',
                'manage branches',
                'view reports',
            ],
            'security' => [
                'manage visitors',
                'manage security',
                'view reports',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            
            foreach ($rolePermissions as $permission) {
                $permission = Permission::where('name', $permission)->first();
                if ($permission) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
