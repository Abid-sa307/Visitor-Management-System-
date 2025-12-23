<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\MultiTenantScope;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VisitorCheckInNotification;
use App\Models\Branch;

class Visitor extends Model
{
    use HasFactory, Notifiable;



    protected $fillable = [
        'company_id',
        'branch_id',
        'name',
        'visitor_company',
        'visitor_category_id',
        'email',
        'phone',
        'face_encoding',
        'face_image',
        'department_id',
        'purpose',
        'person_to_visit',
        'documents',
        'workman_policy',
        'workman_policy_photo',
        'status',
        'in_time',
        'document_path',
        'out_time',
        'last_status',
        'status_changed_at',
        'approved_by',
        'approved_at',
        'rejected_by',
        'reject_reason'
    ];
    
    /**
     * Scope a query to only include approved visitors.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }
    
    /**
     * Check if the visitor is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === 'Approved';
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'documents' => 'array',
        'in_time' => 'datetime',
        'out_time' => 'datetime',
        'status_changed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'in_time',
        'out_time',
        'status_changed_at',
        'approved_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];



    /**
     * Get the branch that the visitor belongs to.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the company that the visitor belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Get the department that the visitor is visiting.
     */
    /**
     * Get the user who approved this visitor.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who rejected this visitor.
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the department that the visitor is visiting.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    protected static function booted()
    {
        static::created(function ($visitor) {
            // Send notification to company user when a visitor checks in
            if ($visitor->status === 'checked_in' && $visitor->company) {
                $companyUser = $visitor->company->users()->first();
                if ($companyUser) {
                    $companyUser->notify(new VisitorCheckInNotification($visitor));
                }
            }
        });
    }


    public function category()
    {
        return $this->belongsTo(VisitorCategory::class, 'visitor_category_id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function logs()
    {
        return $this->hasMany(VisitorLog::class);
    }

    public function securityChecks()
    {
        return $this->hasMany(SecurityCheck::class);
    }

    public function getCanUndoStatusAttribute(): bool
    {
        if (!in_array($this->status, ['Approved', 'Rejected'], true)) {
            return false;
        }

        if (empty($this->last_status) || empty($this->status_changed_at)) {
            return false;
        }

        return $this->status_changed_at instanceof Carbon
            ? $this->status_changed_at->gt(now()->subMinutes(30))
            : Carbon::parse($this->status_changed_at)->gt(now()->subMinutes(30));
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope(new MultiTenantScope);

    //     // Auto-assign company_id for company users
    //     static::creating(function ($model) {
    //         if (auth()->check() && auth()->user()->role !== 'super_admin') {
    //             $model->company_id = auth()->user()->company_id;
    //         }
    //     });
    // }
}
