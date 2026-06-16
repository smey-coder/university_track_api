<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_code',

        'department_name_khmer',
        'department_name_english',

        'description_khmer',
        'description_english',

        'status',
    ];

    /**
     * Relationship: Department has many Students
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
