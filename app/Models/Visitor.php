<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\MultiTenantScope;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'visitor_category_id',
        'email',
        'phone',
        'photo',
        'department_id',
        'purpose',
        'person_to_visit',
        'documents',
        'workman_policy',
        'workman_policy_photo',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
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
