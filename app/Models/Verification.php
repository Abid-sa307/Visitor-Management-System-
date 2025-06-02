<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    //
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function checkpoint()
    {
        return $this->belongsTo(Checkpoint::class);
    }

}
