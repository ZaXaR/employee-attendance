<?php

// app/Models/AttendanceRecord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in',
        'clock_out',
        'notes',
    ];

    protected $casts = [
        // Casts ensure Carbon instances for formatting in views
        'work_date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}