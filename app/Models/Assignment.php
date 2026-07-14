<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_code',
        'course_id',
        'teacher_id',
        'title',
        'description',
        'due_date',
        'total_score',
        'status',
    ];

    // ================= COURSE =================
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // ================= TEACHER =================
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    protected static function boot()
    {
        parent::boot();


        static::retrieved(function ($assignment) {


            if(
                $assignment->status === "Open"
                &&
                now()->greaterThan(
                    $assignment->due_date
                )
            ){

                $assignment->update([

                    'status'=>'Closed'

                ]);

            }


        });

    }
}