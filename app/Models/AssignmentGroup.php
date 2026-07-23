<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentGroup extends Model
{
    protected $fillable = [
        'assignment_id',
        'group_name',
        'leader_student_id',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function leader()
    {
        return $this->belongsTo(
            Student::class,
            'leader_student_id'
        );
    }

    public function members()
    {
        return $this->hasMany(
            AssignmentGroupMember::class,
            'assignment_group_id'
        );
    }

    public function submission()
    {
        return $this->hasOne(
            AssignmentSubmission::class,
            'group_id'
        );
    }
}