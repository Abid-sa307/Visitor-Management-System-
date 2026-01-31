<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Contracts\AuthenticatableUser as AuthenticatableUserContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements AuthenticatableUserContract
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'company_id', 'branch_id',
        'department_id', // if you keep a single department, optional
        'master_pages', 'otp', 'otp_expires_at', 'otp_verified_at', 'is_super_admin',
        'branch_ids' // For storing multiple branch IDs
    ];
    
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['branches', 'company'];
    
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
    public function branch()      { return $this->belongsTo(Branch::class); } // Keeping for backward compatibility
    public function branches()    { return $this->belongsToMany(Branch::class)->withTimestamps(); }
    public function departments() { return $this->belongsToMany(Department::class); }
    public function department()  { return $this->belongsTo(Department::class, 'department_id'); } // optional single
    
    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(\App\Models\DatabaseNotification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

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

        // Map keys/slugs to nice labels
        $labelsMap = [
            'dashboard' => 'Dashboard',
            'visitors' => 'Visitors',
            'visit_details' => 'Visit Details',
            'visitor_history' => 'Visitor History',
            'visitor_inout' => 'Visitor In/Out',
            'approvals' => 'Approvals',
            'reports' => 'Reports',
            'employees' => 'Employees',
            'visitor_categories' => 'Visitor Categories',
            'departments' => 'Departments',
            'users' => 'Users',
            'security_checks' => 'Security Checks',
            'security_questions' => 'Security Questions',
            'visitor_checkup' => 'Visitor Checkup',
            'qr_code' => 'QR Code'
        ];

        $labels = collect($keys)->map(function ($k) use ($labelsMap) {
            return $labelsMap[$k] ?? ucfirst(str_replace('_', ' ', (string) $k));
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
    // Add these methods to your User model, before the closing brace

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'superadmin']) || $this->is_super_admin === true;
    }

    /**
     * Get the company ID for the user
     *
     * @return int|null
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * Check if user is a company admin
     */
    public function isCompanyAdmin(): bool
    {
        return $this->role === 'company' || $this->hasRole('company');
    }

    /**
     * Check if user is a company user
     */
    public function isCompanyUser(): bool
    {
        return $this->role === 'company_user' || $this->hasRole('company_user');
    }

    /**
     * Check if user is a security guard
     */
    public function isGuard(): bool
    {
        return $this->role === 'guard' || $this->hasRole('guard');
    }

    /**
     * Check if user has any admin role
     */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->isCompanyAdmin();
    }

}
