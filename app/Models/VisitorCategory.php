<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitorCategory extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'v_categories';

    protected $fillable = [
        'name',
        'description',
        'company_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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