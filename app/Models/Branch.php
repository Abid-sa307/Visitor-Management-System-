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

    /**
     * Check if a visitor is created outside operating hours for this branch
     */
    public function isOutsideOperatingHours($visitorCreatedAt = null)
    {
        // If no operating hours are set, return false
        if (!$this->start_time || !$this->end_time) {
            return false;
        }
        
        $createdAt = $visitorCreatedAt ?: now();
        $currentTime = $createdAt->format('H:i');
        $startTime = date('H:i', strtotime($this->start_time));
        $endTime = date('H:i', strtotime($this->end_time));
        
        // Check if current time is outside operating hours
        return $currentTime < $startTime || $currentTime > $endTime;
    }
}
