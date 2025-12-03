<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Contracts\AuthenticatableUser as AuthenticatableUserContract;
use Illuminate\Notifications\Notifiable;

class CompanyUser extends Authenticatable implements AuthenticatableUserContract
{
    use HasFactory, Notifiable;

    protected $table = 'company_users'; // Make sure this table exists in your DB

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'master_pages',
        'can_access_qr_code',
        'can_access_visitor_category',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'master_pages' => 'array',  // Ensuring it's treated as an array
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Get the company ID for the user
     *
     * @return int|null
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }
    
    /**
     * Check if the user has a specific role
     *
     * @param string|array $role
     * @return bool
     */
    public function hasRole($role)
    {
        // For CompanyUser, we'll assume they always have the 'company' role
        // If you have a roles relationship, you can implement it like:
        // return $this->roles()->where('name', $role)->exists();
        
        if (is_array($role)) {
            return in_array('company', $role);
        }
        
        return $role === 'company';
    }
}
