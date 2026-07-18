<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassManager extends Model
{
    use HasFactory;
    protected $table = 'class_managers';
    protected $fillable = [
        'class_id',
        'student_id',
        'assigned_date',
        'status',
    ];

    protected $casts = [
        'assigned_date' => 'date',
    ];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}