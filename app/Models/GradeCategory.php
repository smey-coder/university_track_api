<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeCategory extends Model
{

    protected $fillable = [

        'course_id',

        'name',

        'weight'

    ];


    /*
    |--------------------------------------------------------------------------
    | Course Relationship
    |--------------------------------------------------------------------------
    */

    public function course()
    {

        return $this->belongsTo(
            Course::class
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Assignments
    |--------------------------------------------------------------------------
    */

    public function assignments()
    {

        return $this->hasMany(
            Assignment::class
        );

    }

}