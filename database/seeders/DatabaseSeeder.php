<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Create roles
        $admin = Role::firstOrCreate(['role_code' => 'admin'], ['name' => 'Administrator']);
        $teacher = Role::firstOrCreate(['role_code' => 'teacher'], ['name' => 'Teacher']);
        $student = Role::firstOrCreate(['role_code' => 'student'], ['name' => 'Student']);

        // Create permissions
        $permManageUsers = Permission::firstOrCreate(['permission_code' => 'manage_users'], ['name' => 'Manage Users']);
        $permManageCourses = Permission::firstOrCreate(['permission_code' => 'manage_courses'], ['name' => 'Manage Courses']);
        $permViewAttendance = Permission::firstOrCreate(['permission_code' => 'view_attendance'], ['name' => 'View Attendance']);

        // Attach permissions to roles
        $admin->permissions()->syncWithoutDetaching([$permManageUsers->id, $permManageCourses->id, $permViewAttendance->id]);
        $teacher->permissions()->syncWithoutDetaching([$permManageCourses->id, $permViewAttendance->id]);
        $student->permissions()->syncWithoutDetaching([$permViewAttendance->id]);

        // Assign admin role to test user
        $user->roles()->syncWithoutDetaching([$admin->id]);
    }
}
