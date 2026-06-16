<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 1. ACTIVATE ACCOUNT (REGISTER)
     * Student activates account using student_code
     */
    public function register(Request $request)
    {
        $request->validate([
            'student_code' => 'required|string',
            'department_id' => 'required|integer',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string',
            'password' => 'required|min:6|confirmed',
            'email' => 'nullable|email'
        ]);

        // Check student exists
        $student = Student::where('student_code', $request->student_code)
            ->where('department_id', $request->department_id)
            ->where('date_of_birth', $request->date_of_birth)
            ->where('phone', $request->phone)
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student information not found'
            ], 404);
        }

        // Check already activated
        if ($student->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Account already activated'
            ], 400);
        }

        // Create user account
        $user = User::create([
            'username' => $student->student_code,
            'email' => $student->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'last_login' => null,
        ]);

        // Link student to user
        $student->update([
            'user_id' => $user->id,
            'status' => 'Active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account activated successfully',
            'user' => $user
        ], 201);
    }

    /**
     * 2. LOGIN
     * Login using student_code (username)
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Auth attempt
        if (!Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Student ID or Password'
            ], 401);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is not active'
            ], 403);
        }

        // update last login
        $user->update([
            'last_login' => now()
        ]);

        // create token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * 3. DASHBOARD (Protected)
     */
    public function dashboard(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Welcome to University Track System',
            'user' => $request->user()
        ]);
    }

    /**
     * 4. PROFILE (Protected)
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    /**
     * 5. LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}