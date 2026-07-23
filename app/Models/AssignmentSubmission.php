<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_code',
        'assignment_id',
        'student_id',
        'group_id',
        'submitted_by',
        'file_path',
        'content',
        'submitted_at',
        'score',
        'feedback',
        'status',
        'graded_by',
        'graded_at',
    ];

    // ================= ASSIGNMENT =================
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    // ================= STUDENT =================
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function group()
    {
        return $this->belongsTo(
            AssignmentGroup::class,
            'group_id'
        );
    }

    public function submittedBy()
    {
        return $this->belongsTo(
            Student::class,
            'submitted_by'
        );
    }
    public function grader()
    {
        return $this->belongsTo(
            Teacher::class,
            'graded_by'
        );
    }

    public function submitter()
    {
        return $this->belongsTo(
            Student::class,
            'submitted_by'
        );
    }
}