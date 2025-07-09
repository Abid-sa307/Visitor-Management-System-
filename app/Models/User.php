<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🧩 Fix the missing relationships below:

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function department()
    {
        return $this->belongsToMany(\App\Models\Department::class);
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

}
