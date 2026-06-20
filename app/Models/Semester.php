<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentClass;
use App\Models\AcademicYear;
use App\Models\SubjectSchedule;
class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';
    protected $fillable = [
        'academic_year_id',
        'semester_name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classes()
    {
        return $this->hasMany(StudentClass::class);
    }

    public function subjectSchedules()
    {
        return $this->hasMany(SubjectSchedule::class);
    }
}