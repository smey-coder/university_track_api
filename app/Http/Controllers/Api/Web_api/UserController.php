<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    /**
     * Display all users
     */
    public function index()
    {
        try {

            $users = User::with('roles')
                ->latest()
                ->paginate(10);


            return response()->json([

                'success'=>true,

                'data'=>$users->items(),

                'pagination'=>[

                    'current_page'=>$users->currentPage(),

                    'total_pages'=>$users->lastPage(),

                    'total'=>$users->total()

                ]

            ],200);


        }catch(\Exception $e){

            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }
    }





    /**
     * Create User
     */
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {


            $request->validate([

                'username'=>'required|string|max:255',

                'email'=>'required|email|unique:users,email',

                'password'=>'required|min:6',

                'role'=>'required|exists:roles,name'

            ]);



            $user = User::create([

                'username'=>$request->username,

                'email'=>$request->email,

                'password'=>Hash::make(
                    $request->password
                ),

                'is_active'=>$request->is_active ?? true

            ]);




            // Assign Spatie Role

            $user->assignRole(
                $request->role
            );




            DB::commit();



            return response()->json([


                'success'=>true,

                'message'=>'User created successfully',

                'data'=>$user->load('roles')


            ],201);



        }catch(\Exception $e){


            DB::rollBack();


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }







    /**
     * Show User
     */
    public function show(string $id)
    {

        try {


            $user = User::with([

                'roles.permissions'

            ])

            ->findOrFail($id);



            return response()->json([


                'success'=>true,

                'data'=>$user


            ],200);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'User not found'

            ],404);
        }
    }
    /**
     * Update User
     */
    public function update(Request $request,string $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $request->validate([
                'username'=>'required|string|max:255',
                'email'=>
                'required|email|unique:users,email,'.$id,
                'role'=>
                'required|exists:roles,name'
            ]);

            $user->update([
                'username'=>$request->username,
                'email'=>$request->email,
                'is_active'=>
                $request->is_active ?? $user->is_active
            ]);
            // Update Password if provided
            if($request->password){
                $user->update([

                    'password'=>
                    Hash::make($request->password)

                ]);

            }
            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {
                $teacher->update([
                    'status' => $request->is_active ? 'Active' : 'Inactive'
                ]);
            }

            $student = Student::where('user_id', $user->id)->first();

            if ($student) {
                $student->update([
                    'status' => $request->is_active ? 'Active' : 'Pending'
                ]);
            }

            // Replace old role
            $user->syncRoles([
                $request->role

            ]);
            DB::commit();
            return response()->json([
                'success'=>true,
                'message'=>'User updated successfully',
                'data'=>$user->load('roles')

            ],200);


        }catch(\Exception $e){



            DB::rollBack();


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }








    /**
     * Delete User
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            // Update Student Account
            $student = Student::where('user_id', $user->id)->first();

            if($student){

                $student->update([

                    'user_id' => null,

                    'status' => 'Inactive'

                ]);

            }
            // Update Teacher Account
            $teacher = Teacher::where('user_id', $user->id)->first();
            if($teacher){
                $teacher->update([
                    'user_id' => null,
                    'status' => 'Inactive'

                ]);

            }

            // Remove roles
            $user->syncRoles([]);

            // Delete tokens
            $user->tokens()->delete();

            // Delete User
            $user->delete();

            return response()->json([

                'success'=>true,

                'message'=>'User deleted successfully'

            ],200);



        }catch(\Exception $e){

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()

            ],500);
        }

    }
    /**
     * Get Roles for dropdown
     * React Create/Update Modal use this
     */
    public function roles()
    {

        try {


            $roles = Role::all();



            return response()->json([

                'success'=>true,

                'data'=>$roles

            ]);


        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }


}