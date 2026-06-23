<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'attendance_code',
        'session_id',
        'student_id',
        'status',
        'remark',
        'check_in',
        'check_out',
    ];

    // ================= SESSION =================
    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'session_id');
    }

    // ================= STUDENT =================
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}