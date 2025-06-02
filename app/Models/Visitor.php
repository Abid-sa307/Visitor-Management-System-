<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    //
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(VisitorCategory::class, 'visitor_category_id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function logs()
    {
        return $this->hasMany(VisitorLog::class);
    }

}
