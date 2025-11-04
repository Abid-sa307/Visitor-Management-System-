<?php

// app/Models/SecurityCheck.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityCheck extends Model
{
    protected $fillable = [
        'visitor_id', 
        'questions', 
        'responses', 
        'security_officer_name',
        'visitor_photo',
        'signature',
        'officer_badge',
        'photo_responses'
    ];

    protected $casts = [
        'questions' => 'array',
        'responses' => 'array',
        'photo_responses' => 'array',
    ];
    
    protected $appends = ['visitor_photo_url', 'signature_url'];
    
    protected function getVisitorPhotoUrlAttribute()
    {
        return $this->visitor_photo ? asset('storage/' . $this->visitor_photo) : null;
    }
    
    protected function getSignatureUrlAttribute()
    {
        return $this->signature ? asset('storage/' . $this->signature) : null;
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
