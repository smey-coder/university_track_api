<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudentClass;

class Student extends Model
{
    protected $table= 'students';
    protected $fillable = [
        'student_code',
        'department_id',
        'class_id',
        'semester_id',
        'first_name_khmer',
        'last_name_khmer',
        'first_name_english',
        'last_name_english',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'photo',
        'enrollment_date',
        'status',
        'user_id',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    protected $appends = ['photo_url'];
    
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset
            ('storage/' . $this->photo);
        }

        return asset('images/default-avatar.png');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
     public function classManagerRecord()
    {
        return $this->hasOne(ClassManager::class);
    }
     public function classes()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class,'semester_id');
    }
    public function schedules()
    {
        return $this->hasMany(SubjectSchedule::class,'class_id');
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    public function studentClass()
    {
        return $this->belongsTo(
            StudentClass::class,
            'class_id'
        );
    }
}
