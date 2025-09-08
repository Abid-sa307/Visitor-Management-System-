<@php

// app/Models/Concerns/HasDateRange.php (optional trait)
namespace App\Models\Concerns;

use Carbon\Carbon;

trait HasDateRange
{
    public function scopeDateRange($query, string $column, ?string $from, ?string $to)
    {
        if (!$from && !$to) return $query;

        $start = $from ? Carbon::parse($from)->startOfDay() : null;
        $end   = $to   ? Carbon::parse($to)->endOfDay()   : null;

        return $query->when($start, fn($q) => $q->where($column, '>=', $start))
                     ->when($end,   fn($q) => $q->where($column, '<=', $end));
    }
}
