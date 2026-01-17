<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'branch_id',
        'check_type',
        'question',
        'type',
        'options',
        'is_required',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}