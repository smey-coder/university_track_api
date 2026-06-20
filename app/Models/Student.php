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
        'date_of_birth',
        'phone',
        'email',
        'address',
        'status',
        'user_id'   // ✅ ADD THIS
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
        return $this->belongsTo(
            Semester::class,
            'semester_id'
        );
    }
}
