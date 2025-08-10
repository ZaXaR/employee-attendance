<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',         // ✅ добавлено
        'job_role_id',   // ✅ добавлено
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 📌 Связь с посещениями
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    // 📌 Связь с должностью
    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }

    // 📌 (опционально) Связь с локацией
    // public function location()
    // {
    //     return $this->belongsTo(Location::class);
    // }
}