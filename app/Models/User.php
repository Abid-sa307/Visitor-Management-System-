<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'company_id', 'branch_id',
        'department_id', // if you keep a single department, optional
        'master_pages', 'otp', 'otp_expires_at', 'otp_verified_at', 'is_super_admin'
    ];
    
    protected $dates = [
        'otp_expires_at',
        'otp_verified_at',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'master_pages'      => 'array', // JSON <-> array
    ];

    /** Relationships */
    public function company()     { return $this->belongsTo(Company::class); }
    public function branch()      { return $this->belongsTo(Branch::class); }
    public function departments() { return $this->belongsToMany(Department::class); }
    public function department()  { return $this->belongsTo(Department::class, 'department_id'); } // optional single

    /**
     * Check if OTP verification is required for this user
     */
    public function requiresOtpVerification(): bool
    {
        return $this->is_super_admin && $this->role === 'admin';
    }

    /** Normalize master pages array */
    public function getMasterPagesListAttribute(): array
    {
        $pages = is_array($this->master_pages) ? $this->master_pages : json_decode($this->master_pages ?? '[]', true);
        return is_array($pages) ? $pages : [];
    }

    /**
     * Human-readable Page Access:
     * - "All" for super admins
     * - comma-separated labels for others
     * - "—" if none
     */
    public function getMasterPagesDisplayAttribute(): string
    {
        if (in_array($this->role, ['super_admin','superadmin'], true)) {
            return 'All';
        }

        $keys = $this->master_pages_list;
        if (empty($keys)) return '—';

        // Map keys/slugs to nice labels (adjust to your app)
        $labelsMap = [
            // 'users' => 'Users',
            // 'companies' => 'Companies',
            // 'departments' => 'Departments',
            // 'reports' => 'Reports',
            // add all your page keys here...
        ];

        $labels = collect($keys)->map(function ($k) use ($labelsMap) {
            return $labelsMap[$k] ?? ucfirst(str_replace('_',' ', (string) $k));
        });

        return $labels->join(', ');
    }

    /** Visibility helper */
    public function scopeVisibleTo($query, $user)
    {
        if (in_array($user->role, ['superadmin','super_admin'], true)) return $query;
        return $query->where('company_id', $user->company_id)->where('role', '!=', 'superadmin');
    }

    // IMPORTANT: remove any custom hasRole() you added.
    // Using the method below will SHADOW Spatie’s version. Delete it if present.
    // public function hasRole($role) { ... }  // <-- REMOVE THIS
}
