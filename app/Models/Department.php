<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Student;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_code',
        'department_name_khmer',
        'department_name_english',
        'description',
        'status',
    ];

    // ✅ FORCE INTEGER TYPE FOR API
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Relationship: Department has many Students
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}