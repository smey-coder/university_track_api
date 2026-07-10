<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'username',
        'email',
        'role',
        'password',
        'is_active',
        'last_login'
    ];

    /**
     * Hidden fields (do not return in API)
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
     * Relationship (optional)
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    // }

    // public function permissions()
    // {
    //     // Return a collection of permissions gathered from the user's roles.
    //     return $this->roles()
    //         ->with('permissions')
    //         ->get()
    //         ->pluck('permissions')
    //         ->flatten()
    //         ->unique('id')
    //         ->values();
    // }

    // public function hasRole($role)
    // {
    //     return $this->roles()->where('name', $role)->exists();
    // }
}