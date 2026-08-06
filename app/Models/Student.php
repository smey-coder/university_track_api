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
            // return asset
            // ('storage/' . $this->photo);
            return url('/api/media/' . $this->photo);
        }
        // ពិនិត្យមើលថាតើមានឈ្មោះ File ក្នុង DB និង មាន File ពិតប្រាកដក្នុង Storage ឬអត់
        // if ($this->photo && Storage::disk('public')->exists($this->photo)) {
        //     return url('/api/media/' . $this->photo);
        // }

        // return asset('images/default-avatar.png');
        // try {
        //     if (!empty($this->photo) && Storage::disk('public')->exists($this->photo)) {
        //         return url('/api/media/' . $this->photo);
        //     }
        // } catch (\Exception $e) {
        //     // ប្រសិនបើមានបញ្ហា Storage System វានឹងរំលងមកប្រើ Fallback
        // }

        // // បង្កើត Avatar តាមឈ្មោះ ឬ ប្រើ Default Image
        $name = $this->first_name_english ?? $this->last_name_english ?? 'Student';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random';
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
    /*
    |--------------------------------------------------------------------------
    | Assignment Groups
    |--------------------------------------------------------------------------
    */

    public function assignmentGroups()
    {
        return $this->hasMany(
            AssignmentGroup::class,
            'leader_student_id'
        );
    }

    public function assignmentGroupMembers()
    {
        return $this->hasMany(
            AssignmentGroupMember::class,
            'student_id'
        );
    }
    public function gradebooks()
    {
        return $this->hasMany(
            Gradebook::class
        );
    }
    public function transcripts()
    {
        return $this->hasMany(
            Transcript::class
        );
    }
    public function graduation()
    {
        return $this->hasOne(
            Graduation::class
        );
    }
    
}
