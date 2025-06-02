<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
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

}
