<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    /**
     * Spatie Permission Guard
     */
    protected $guard_name = 'sanctum';


    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'is_active',
        'last_login'
    ];


    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Cast attributes
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];


    /**
     * Relationship with Student
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

}