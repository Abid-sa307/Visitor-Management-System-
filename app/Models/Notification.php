<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'company_id',
        'visitor_id',
        'type',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    // Notification types
    const TYPE_VISITOR_CREATED = 'created';
    const TYPE_VISITOR_APPROVED = 'approved';
    const TYPE_VISITOR_CHECK_IN = 'check_in';
    const TYPE_VISITOR_CHECK_OUT = 'check_out';
}
