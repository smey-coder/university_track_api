<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $table = 'attendance_sessions';

    protected $fillable = [
        'session_code',
        'attendance_code',
        'class_id',
        'course_id',
        'teacher_id',
        'session_date',
        'start_time',
        'end_time',
        'remark',
        'status',
        'qr_code',
    ];

    // ================= CLASS =================
    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    // ================= COURSE =================
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // ================= TEACHER (optional) =================
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    // ================= ATTENDANCE RECORDS =================
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }
     // ================= HELPER =================
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isFinished()
    {
        return $this->status === 'finished';
    }
}