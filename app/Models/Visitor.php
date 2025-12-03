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

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['company', 'department', 'branch', 'approvedBy', 'rejectedBy'];

    protected $fillable = [
        'company_id',
        'branch_id',
        'name',
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
        'rejected_by',
        'reject_reason'
    ];

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
        'face_encoding' => 'array',
    ];

    /**
     * Get the face_encoding attribute.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function getFaceEncodingAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        // If it's already an array, return it as is
        if (is_array($value)) {
            return $value;
        }

        // If it's a JSON string, decode it
        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

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
