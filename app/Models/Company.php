<?php

namespace App\Models;
   

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    //

     use HasFactory;
     
    protected $fillable = [
        'name',
        'address',
        'contact_number',
        'logo',
        'gst_number',
        'website',
        'email',
        'notification_settings',
    ];

    protected $casts = [
        'notification_settings' => 'array',
    ];


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
