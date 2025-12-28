<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'company';

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'contact_number',
        'logo',
        'gst_number',
        'website',
        'notification_settings',
        'face_recognition_enabled',
        'mail_service_enabled',
        'auto_approve_visitors',
        'security_check_service',
        'security_checkin_type',
        'branch_start_date',
        'branch_end_date',
        'operation_start_time',
        'operation_end_time',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'notification_settings' => 'array',
        'face_recognition_enabled' => 'boolean',
        'mail_service_enabled' => 'boolean',
        'auto_approve_visitors' => 'boolean',
        'security_check_service' => 'boolean',
        'branch_start_date' => 'date',
        'branch_end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active companies.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    /**
     * Automatically hash the password when it's set.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // protected static function booted()
    // {
    //     // Apply tenant filter
    //     static::addGlobalScope(new MultiTenantScope);

    //     // Auto-assign company_id when creating (for non-super_admins)
    //     static::creating(function ($model) {
    //         if (Auth::check() && Auth::user()->role !== 'super_admin') {
    //             $model->id = Auth::user()->company_id; // A company is its own tenant
    //         }
    //     });
    // }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function visitorCategories()
    {
        return $this->hasMany(VisitorCategory::class);
    }

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
    
    /**
     * Get the employees for the company.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    
    /**
     * Check if a visitor is created outside operating hours
     */
    public function isVisitorOutsideOperatingHours($visitorCreatedAt = null)
    {
        // If no operating hours are set, return false
        if (!$this->operation_start_time || !$this->operation_end_time) {
            return false;
        }
        
        $createdAt = $visitorCreatedAt ?: now();
        $currentTime = $createdAt->format('H:i');
        $startTime = $this->operation_start_time;
        $endTime = $this->operation_end_time;
        
        // Check if current time is outside operating hours
        return $currentTime < $startTime || $currentTime > $endTime;
    }
}
