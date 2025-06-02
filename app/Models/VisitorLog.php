<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    //
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

}
