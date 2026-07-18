<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;


    protected $table = 'classes';


    protected $fillable = [

        'academic_year_id',
        'department_id',
        'class_name',
        'room',
        'max_students',
        'status',

    ];



    // Academic Year

    public function academicYear()
    {
        return $this->belongsTo(
            AcademicYear::class,
            'academic_year_id'
        );
    }



    // Department

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }

    // Students

    public function students()
    {
        return $this->hasMany(
            Student::class,
            'class_id'
        );
    }
    // Subject Schedules

    public function schedules()
    {
        return $this->hasMany(
            SubjectSchedule::class,
            'class_id'
        );
    }
    // Class Manager

    public function classManagers()
    {
        return $this->hasMany(
            ClassManager::class,
            'class_id'
        );
    }
    // Multiple Semester
    public function classSemesters()
    {
        return $this->hasMany(
            ClassSemester::class,
            'class_id'
        );
    }
    protected $appends = [
        'student_count'
    ];
    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }

}