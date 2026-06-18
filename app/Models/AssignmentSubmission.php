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
        'file_path',
        'submitted_at',
        'score',
        'feedback',
        'status',
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
}