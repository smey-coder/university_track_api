<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'department_id',
        'teacher_id',
        'course_name',
        'credits',
        'description',
        'status',
    ];
    public $timestamps = true;

    // ================= RELATION: Department =================
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // ================= RELATION: Teacher =================
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedules()
    {
        return $this->hasMany(SubjectSchedule::class);
    }
}
