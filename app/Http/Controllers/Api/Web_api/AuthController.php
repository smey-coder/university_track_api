<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{
    // ============================
    // Register
    // ============================
    public function register(Request $request)
    {
        $request->validate([

            'username'=>'required|string|max:255',

            'email'=>'required|email|unique:users',

            'password'=>'required|min:6'

        ]);

        $user = User::create([

            'username'=>$request->username,

            'email'=>$request->email,

            'password'=>Hash::make($request->password),

        ]);

        // Assign default role
        $studentRole = Role::where('name','Student')
            ->where('guard_name','sanctum')
            ->first();
        if($studentRole){

            $user->assignRole($studentRole);

        }
        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;
        return response()->json([

            'success'=>true,

            'message'=>'User registered successfully',

            'token'=>$token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,

                'role' => strtolower(
                    $user->getRoleNames()->first()
                ),

                'permissions' => $user->getAllPermissions()
                    ->pluck('name')
            ]
        ],201);

    }
    // ============================
    // Activate Student Account
    // ============================
    public function activateStudent(Request $request)
    {
        $request->validate([

            'student_code'=>'required|string',

            'department_id'=>'required|integer',

            'date_of_birth'=>'required|date',

            'phone'=>'required|string',

            'password'=>'required|min:6|confirmed',

            'email'=>'nullable|email'

        ]);



        $student = Student::where('student_code',$request->student_code)
            ->where('department_id',$request->department_id)
            ->where('date_of_birth',$request->date_of_birth)
            ->where('phone',$request->phone)
            ->first();

       
        if(!$student){
            return response()->json([
                'success'=>false,
                'message'=>'Student information does not match'

            ],404);

        }

        if($student->user_id){

            return response()->json([

                'success'=>false,

                'message'=>'Account already activated'

            ],400);

        }



        $user = User::create([
            'username'=>$student->student_code,
            'email'=>$request->email ?? $student->email,
            'password'=>Hash::make($request->password),
            'is_active' => true,
            'last_login' => null,

        ]);



        $role = Role::where('name','Student')
            ->where('guard_name','sanctum')
            ->first();



        if($role){

            $user->assignRole($role);

        }



        $student->update([

            'user_id'=>$user->id,

            'status'=>'Active'

        ]);



        return response()->json([

            'success'=>true,

            'message'=>'Student account activated successfully'

        ]);

    }
    // ============================
    // Activate Teacher Account
    // ============================
    public function activateTeacher(Request $request)
    {
        try {

            // Validation
            $request->validate([

                'teacher_code' => 'required|string',

                'department_id' => 'required|integer',

                'date_of_birth' => 'required|date',

                'phone' => 'required|string',

                'password' => 'required|min:6|confirmed',

                'email' => 'nullable|email'

            ]);



            // Find teacher created by Admin
            $teacher = Teacher::where('teacher_code', $request->teacher_code)

                ->where('department_id', $request->department_id)

                ->where('date_of_birth', $request->date_of_birth)

                ->where('phone', $request->phone)

                ->first();



            // Teacher not found
            if (!$teacher) {

                return response()->json([

                    'success' => false,

                    'message' => 'Teacher information does not match our records'

                ], 404);

            }



            // Check already activated
            if ($teacher->user_id) {

                return response()->json([

                    'success' => false,

                    'message' => 'Teacher account already activated'

                ], 400);

            }



            // Create User Account

            $user = User::create([

                'username' => $teacher->teacher_code,

                'email' => $request->email ?? $teacher->email,

                'password' => Hash::make($request->password)

            ]);




            // Assign Teacher Role

            $teacherRole = Role::where('name', 'Teacher')

                ->where('guard_name', 'sanctum')

                ->first();



            if ($teacherRole) {

                $user->assignRole($teacherRole);

            }



            // Link Teacher with User

            $teacher->update([

                'user_id' => $user->id,

                'account_status' => 'active'

            ]);




            // Create Token

            $token = $user

                ->createToken('auth_token')

                ->plainTextToken;



            return response()->json([

                'success' => true,

                'message' => 'Teacher account activated successfully',


                'token' => $token,


                'user' => [

                    'id' => $user->id,

                    'username' => $user->username,

                    'email' => $user->email,

                    'role' => 'Teacher'

                ]

            ], 201);



        } catch (\Exception $e) {


            return response()->json([

                'success' => false,

                'message' => 'Failed to activate teacher account',

                'error' => $e->getMessage()

            ], 500);

        }
    }
    // ============================
    // Login
    // ============================
    public function login(Request $request)
    {

        $request->validate([

            'email'=>'required|email',

            'password'=>'required'

        ]);

        if(!Auth::attempt(
            $request->only('email','password')
        )){

            return response()->json([

                'success'=>false,

                'message'=>'Invalid credentials'

            ],401);

        }

        $user = User::where(
            'email',
            $request->email
        )->first();
        // Remove old tokens
        $user->tokens()->delete();
        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;
        return response()->json([
            'success'=>true,
            'message'=>'Login successful',
            'token'=>$token,
            'user'=>[
                'id'=>$user->id,
                'username'=>$user->username,
                'email'=>$user->email,
                'roles'=>$user
                    ->getRoleNames(),
                'permissions'=>$user
                    ->getAllPermissions()
                    ->pluck('name')
            ]
        ]);

    }
    // ============================
    // Dashboard
    // ============================
    public function dashboard(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'success'=>true,
            'message'=>'Welcome to dashboard',
            'user'=>[
                'id'=>$user->id,
                'username'=>$user->username,
                'email'=>$user->email,
                'roles'=>$user
                    ->getRoleNames(),
                'permissions'=>$user
                    ->getAllPermissions()
                    ->pluck('name')
            ]
        ]);

    }
    // ============================
    // Logout
    // ============================
    public function logout(Request $request)
    {

        $request
            ->user()
            ->currentAccessToken()
            ->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Logged out successfully'

        ]);
    }
}