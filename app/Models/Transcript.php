<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{

    protected $fillable=[

        'student_id',

        'semester_id',

        'academic_year_id',

        'total_credits',

        'semester_gpa',

        'overall_gpa'

    ];



    public function student()
    {
        return $this->belongsTo(
            Student::class
        );
    }



    public function semester()
    {
        return $this->belongsTo(
            Semester::class
        );
    }



    public function academicYear()
    {
        return $this->belongsTo(
            AcademicYear::class
        );
    }

}