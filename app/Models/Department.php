<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

}
