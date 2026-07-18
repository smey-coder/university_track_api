<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ClassSemester extends Model
{
    use HasFactory;


    protected $fillable = [

        'class_id',
        'semester_id',
        'academic_year_id'

    ];



    public function studentClass()
    {
        return $this->belongsTo(
            StudentClass::class,
            'class_id'
        );
    }



    public function semester()
    {
        return $this->belongsTo(
            Semester::class,
            'semester_id'
        );
    }



    public function academicYear()
    {
        return $this->belongsTo(
            AcademicYear::class,
            'academic_year_id'
        );
    }

}