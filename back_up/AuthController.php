<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth; 
 
class AuthController extends Controller 
{ 
    // 1. Register 
    public function register(Request $request) 
    { 
        $request->validate([ 
            'username' => 'required|string|max:255', 
            'email' => 'required|email|unique:users', 
            'password' => 'required|min:6' 
        ]); 
 
        $user = User::create([ 
            'username' => $request->username, 
            'email' => $request->email, 
            'password' => Hash::make($request->password),
            'role' => 'student' // Default role for new signups; change to 'teacher' or 'admin' as needed
        ]); 
 
        // Automatically log them in by issuing a token right after registration
        $token = $user->createToken('auth_token')->plainTextToken; 
 
        return response()->json([ 
            'message' => 'User registered successfully', 
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role //Included for React mapping
            ]
        ], 201); 
    } 
 
    // 2. Login 
    public function login(Request $request) 
    { 
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) { 
            return response()->json([ 
                'message' => 'Invalid credentials' 
            ], 401); 
        } 
 
        $user = User::where('email', $request->email)->first(); 
        
        // Optional: Clean up old active tokens for this user to keep data clean
        $user->tokens()->delete(); 
        
        $token = $user->createToken('auth_token')->plainTextToken; 
 
        return response()->json([ 
            'message' => 'Login successful', 
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role // 👈 Included for React mapping
            ]
        ]); 
    } 
 
    // 3. Dashboard (Protected) 
    public function dashboard(Request $request) 
    { 
        return response()->json([ 
            'message' => 'Welcome to dashboard', 
            'user' => $request->user() // This natively returns all authenticated column values including 'role'
        ]); 
    } 
 
    // 4. Logout 
    public function logout(Request $request) 
    { 
        // Deletes the specific token used to make this request
        $request->user()->currentAccessToken()->delete(); 
        
        return response()->json([ 
            'message' => 'Logged out successfully' 
        ]); 
    } 
}