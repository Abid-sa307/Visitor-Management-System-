<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitorCategory extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    // app/Models/VisitorCategory.php
    protected $table = 'visitor_categories';

    protected $fillable = [
        'name',
        'company_id',
        'branch_id',
        'description',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

   
    public function scopeForCompany($query, $companyId = null)
    {
        if (auth()->user()->hasRole('superadmin') && $companyId) {
            return $query->where('company_id', $companyId);
        }
        
        if (auth()->guard('company')->check()) {
            return $query->where('company_id', auth()->guard('company')->id());
        }

        return $query;
    }
}