<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        $permissions = [

            // Dashboard
            'dashboard.view',


            // User Management
            'user.view',
            'user.create',
            'user.update',
            'user.delete',


            // Role Management
            'role.view',
            'role.create',
            'role.update',
            'role.delete',


            // Permission Management
            'permission.create',
            'permission.update',
            'permission.delete',


            // Department Management
            'department.view',
            'department.create',
            'department.update',
            'department.delete',


            // Teacher Management
            'teacher.view',
            'teacher.create',
            'teacher.update',
            'teacher.delete',


            // Student Management
            'student.view',
            'student.create',
            'student.update',
            'student.delete',


            // Class Management
            'class.view',
            'class.create',
            'class.update',
            'class.delete',


            // Class Manager
            'class_manager.view',
            'class_manager.create',
            'class_manager.update',
            'class_manager.delete',


            // Course Management
            'course.view',
            'course.create',
            'course.update',
            'course.delete',


            // Assignment Management
            'assignment.view',
            'assignment.create',
            'assignment.update',
            'assignment.delete',


            // Schedule Management
            'schedule.view',
            'schedule.create',
            'schedule.update',
            'schedule.delete',


            // Attendance
            'attendance.view',
            'attendance.create',
            'attendance.update',
            'attendance.delete',

        ];



        foreach($permissions as $permission){

            Permission::firstOrCreate([

                'name'=>$permission,

                'guard_name'=>'sanctum'

            ]);

        }


        // Clear cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

    }
}