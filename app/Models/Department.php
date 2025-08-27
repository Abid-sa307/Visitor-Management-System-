<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\MultiTenantScope;
use Illuminate\Support\Facades\Auth;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id',
    ];

    // protected static function booted()
    // {
    //     // Apply tenant filter automatically
    //     static::addGlobalScope(new MultiTenantScope);

    //     // Auto-assign company_id for non-super_admins
    //     static::creating(function ($model) {
    //         if (Auth::check() && Auth::user()->role !== 'super_admin') {
    //             $model->company_id = Auth::user()->company_id;
    //         }
    //     });
    // }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
