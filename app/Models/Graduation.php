<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Graduation extends Model
{

    protected $fillable=[

        'student_id',

        'graduation_date',

        'completed_credits',

        'final_gpa',

        'status'

    ];



    public function student()
    {
        return $this->belongsTo(
            Student::class
        );
    }
    public function certificate()
    {
        return $this->hasOne(
            Certificate::class
        );
    }

}