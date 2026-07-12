<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // Create permissions
        $this->call([
            PermissionSeeder::class,
        ]);


        // Create Admin role if not exists
        $admin = Role::firstOrCreate([
            'name'=>'Admin',
            'guard_name'=>'sanctum'
        ]);



        // Find test user
        $user = User::find(25);



        if($user){

            // Assign Spatie role
            $user->assignRole($admin);

        }


    }
}