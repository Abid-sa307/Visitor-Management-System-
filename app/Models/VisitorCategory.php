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
    protected $table = 'visitor_categories';

    protected $fillable = [
        'name',
        'company_id'
    ];

    // Active scope returns all categories since we don't have an is_active column
    public function scopeActive($query)
    {
        return $query;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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