<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentClass;
use App\Models\Semester;
use App\Models\SubjectSchedule;
class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function classes()
    {
        return $this->hasMany(StudentClass::class, 'academic_year_id');
    }
    public function subjectSchedules()
    {
        return $this->hasMany(
            SubjectSchedule::class,
            'academic_year_id'
        );
    }
    public function classSemesters()
{
    return $this->hasMany(
        ClassSemester::class,
        'academic_year_id'
    );
}
}