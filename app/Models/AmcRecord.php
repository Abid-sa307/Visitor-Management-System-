<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmcRecord extends Model
{
    protected $fillable = [
        'company_id',
        'branch_id',
        'package_name',
        'amount',
        'start_date',
        'end_date',
        'payment_date',
        'payment_mode',
        'transaction_reference',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /** Auto-compute status based on dates */
    public function getComputedStatusAttribute(): string
    {
        $today = now()->toDateString();
        if ($this->end_date && $this->end_date->lt(now()))
            return 'expired';
        if ($this->start_date && $this->start_date->gt(now()))
            return 'upcoming';
        return 'active';
    }
}
