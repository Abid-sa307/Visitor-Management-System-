<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\MultiTenantScope; // ✅ Add this


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'company_id',
        'department_id', // optional
        'master_pages',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'master_pages' => 'array', // automatically decode JSON to array
    ];

    /**
     * Apply MultiTenantScope globally so data is filtered
     */
   

    /**
     * Relationships
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    /**
     * Accessors for Master Pages
     */
    public function getMasterPagesListAttribute()
    {
        $pages = is_array($this->master_pages) ? $this->master_pages : json_decode($this->master_pages, true);
        return is_array($pages) ? $pages : [];
    }

    public function getMasterPagesDisplayAttribute()
    {
        $pages = $this->master_pages_list;
        $count = count($pages);

        return $count > 0
            ? "{$count} (" . implode(', ', $pages) . ")"
            : "0";
    }

    public function scopeVisibleTo($query, $user)
    {
        if ($user->role === 'superadmin') {
            return $query;
        }

        return $query->where('company_id', $user->company_id)
                    ->where('role', '!=', 'superadmin');
    }

// protected static function booted()
// {
//     // Add multi-tenant scope
//     static::addGlobalScope(new \App\Scopes\MultiTenantScope);

//     // Auto-assign company_id for non-super-admins
//     static::creating(function ($model) {
//         if (auth()->check() && auth()->user()->role !== 'super_admin') {
//             $model->company_id = auth()->user()->company_id;
//         }
//     });
// }


}
