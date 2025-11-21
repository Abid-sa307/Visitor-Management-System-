<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\MultiTenantScope;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact_number',
        'logo',
        'gst_number',
        'website',
        'email',
        'notification_settings',
    ];

    protected $casts = [
        'notification_settings' => 'array',
    ];

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
}
