<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'class_id',
        'semester_id',
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'max_students',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }
   public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'subject_schedule_id');
    }
}