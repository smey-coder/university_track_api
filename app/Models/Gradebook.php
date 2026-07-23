<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gradebook extends Model
{

    protected $fillable=[

        'course_id',

        'student_id',

        'final_score',

        'letter_grade',

        'gpa'

    ];


    public function course()
    {
        return $this->belongsTo(
            Course::class
        );
    }


    public function student()
    {
        return $this->belongsTo(
            Student::class
        );
    }

}