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
        try {

         $request->validate([

            'student_code'=>'required|string',
            'first_name_english' => 'required|string',
            'last_name_english' => 'required|string',
            'department_id'=>'required|integer',
            'date_of_birth'=>'required|date',
            'phone'=>'required|string',
            'password'=>'required|min:6|confirmed',
            'email'=>'nullable|email'
        ]);

        $student = Student::where('student_code',$request->student_code)
            ->where('first_name_english', $request->first_name_english)
            ->where('last_name_english', $request->last_name_english)
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
            'username' => $student->first_name_english . ' ' . $student->last_name_english,
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate student account',
                'error' => $e->getMessage()
            ], 500);

        }
       
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
            if ($teacher->status === 'Active' || $teacher->user_id !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher account already activated'
                ], 400);
            }
            // Check email already exists
            $email = $request->email ?? $teacher->email;
            if(User::where('email',$email)->exists()){
                return response()->json([
                    'success'=>false,
                    'message'=>'Email already exists. Please use another email.'
                ],400);
            }
            // Create User Account
            $user = User::create([
                'username' => $teacher->first_name_english . ' ' . $teacher->last_name_english,
                'email' => $request->email ?? $teacher->email,
                'password' => Hash::make($request->password),
                'is_active' => true,
                'last_login' => null,

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
                'status' => 'Active'
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
        // Check email + password

        if(!Auth::attempt(
            $request->only('email','password')
        )){
            return response()->json([

                'success'=>false,
                'message'=>'Invalid credentials'

            ],401);
        }
        // Get active user
        $user = User::where('email',$request->email)
            ->where('is_active',true)
            ->first();
        if(!$user){
            return response()->json([

                'success'=>false,
                'message'=>'Account is not activated'

            ],403);
        }
        // Get Role
        $role = $user->getRoleNames()->first();
        // Check Student Status
        if($role === "Student"){
            $student = Student::where('user_id',$user->id)

                ->where('status','Active')

                ->first();

            if(!$student){
                return response()->json([

                    'success'=>false,

                    'message'=>'Student account is not active'

                ],403);
            }
        }

        // Check Teacher Status

        if($role === "Teacher"){

            $teacher = Teacher::where('user_id',$user->id)

                ->where('status','Active')

                ->first();
            if(!$teacher){

                return response()->json([

                    'success'=>false,

                    'message'=>'Teacher account is not active'

                ],403);
            }
        }
        // Remove old tokens

        $user->tokens()->delete();

        // Create Token

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
                'role'=>$role,
                'roles'=>$user
                    ->getRoleNames(),
                'permissions'=>$user
                    ->getAllPermissions()
                    ->pluck('name')
            ]
        ],200);
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