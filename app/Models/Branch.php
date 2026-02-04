<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'address', 'phone', 'email', 'start_date', 'end_date', 'start_time', 'end_time'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
