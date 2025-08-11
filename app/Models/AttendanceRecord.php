<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in',
        'clock_out',
        'notes',
        'location_id',
        'break_time',
    ];

    protected $casts = [
        'work_date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    // Accessor: formatted break time (e.g. "1h 30min")
    public function getFormattedBreakAttribute(): string
    {
        $minutes = $this->break_time ?? 0;

        if ($minutes === 0) {
            return 'No break';
        }

        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        return trim("{$hours}h {$remaining}min");
    }

    // Accessor: total work minutes excluding break
    public function getTotalWorkMinutesAttribute(): int
    {
        if (!($this->clock_in && $this->clock_out)) {
            return 0;
        }

        $worked = $this->clock_out->diffInMinutes($this->clock_in);
        $break = $this->break_time ?? 0;

        return max(0, $worked - $break);
    }

    // Accessor: formatted total work time (e.g. "7h 30min")
    public function getFormattedWorkTimeAttribute(): string
    {
        $minutes = $this->total_work_minutes;

        if ($minutes === 0) {
            return '—';
        }

        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        return trim("{$hours}h {$remaining}min");
    }
}