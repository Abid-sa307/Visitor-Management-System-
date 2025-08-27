<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'company_id', 'department_id', 'name', 'designation', 'email', 'phone'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope(new \App\Scopes\MultiTenantScope);
    // }

}
