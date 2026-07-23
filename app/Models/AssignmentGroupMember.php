<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentGroupMember extends Model
{
    protected $fillable = [
        'assignment_group_id',
        'student_id',
        'role',
        'status',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function group()
    {
        return $this->belongsTo(
            AssignmentGroup::class,
            'assignment_group_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}