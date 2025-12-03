<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class CheckSuperAdmin extends Command
{
    protected $signature = 'user:check-superadmin {email?}';
    protected $description = 'Check if a user with the given email is a superadmin';

    public function handle()
    {
        $email = $this->argument('email') ?: 'superadmin@example.com';
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("No user found with email: {$email}");
            return 1;
        }
        
        $this->info("User found: {$user->name} ({$user->email})");
        $this->line("Is Super Admin: " . ($user->is_super_admin ? 'Yes' : 'No'));
        
        if (method_exists($user, 'getRoleNames')) {
            $this->line("Roles: " . $user->getRoleNames()->implode(', '));
        } else {
            $this->warn('Spatie Roles & Permissions not properly set up');
        }
        
        return 0;
    }
}
