<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_code',
        'user_id',
        'department_id',
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
        'hire_date',
        'status',
    ];

    protected $appends = ['full_name_english', 'full_name_khmer', 'photo_url']; // ✅ ADD THIS

    // ================= RELATION: User =================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ================= RELATION: Department =================
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // ================= FULL NAME (English) =================
    public function getFullNameEnglishAttribute()
    {
        return $this->first_name_english . ' ' . $this->last_name_english;
    }

    // ================= FULL NAME (Khmer) =================
    public function getFullNameKhmerAttribute()
    {
        return $this->first_name_khmer . ' ' . $this->last_name_khmer;
    }

    // ================= PHOTO URL (SAFE) =================
    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-user.png');
    }

    public function schedules()
    {
        return $this->hasMany(SubjectSchedule::class);
    }
}