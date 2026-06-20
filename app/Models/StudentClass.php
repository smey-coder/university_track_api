<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    // ================= FILLABLE =================
    protected $fillable = [
        'academic_year_id',
        'semester_id',
        'department_id',
        'class_name',
        'room',
        'max_students',
        'status',
    ];

    // ================= RELATIONS =================

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function schedules()
    {
        return $this->hasMany(SubjectSchedule::class, 'class_id');
    }

    public function classManager()
    {
        return $this->hasOne(ClassManager::class, 'class_id');
    }

    // ================= API HELPER (OPTIONAL) =================

    protected $appends = [
        'student_count'
    ];

    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }
}