<?php

// app/Models/SecurityCheck.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityCheck extends Model
{
    protected $fillable = ['visitor_id', 'questions', 'responses', 'security_officer_name'];

    protected $casts = [
        'questions' => 'array',
        'responses' => 'array',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
