<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login'])->name('api.login'); 

Route::middleware('auth:sanctum')->group(function () { 
Route::get('/dashboard', [AuthController::class, 'dashboard']); 
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete(); // delete all tokens
    return response()->json([
        'success' => true,
        'message' => 'Logged out successfully'
    ]);
});
}); 

//Department
Route::get('/departments', [DepartmentController::class, 'index']);
Route::post('/departments', [DepartmentController::class, 'store']);
Route::get('/departments/{id}', [DepartmentController::class, 'show']);
Route::put('/departments/{id}', [DepartmentController::class, 'update']);
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

//Student
Route::get('/students', [StudentController::class, 'index']);
// Route::post('/students', [StudentController::class, 'store']);
Route::get('/students/{id}', [StudentController::class, 'show']);
// Route::put('/students/{id}', [StudentController::class, 'update']);
// Route::delete('/students/{id}', [StudentController::class, 'destroy']);


// Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return "Admin Dashboard";
//     });

//     Route::post('/departments', [DepartmentController::class, 'store']);
// });
// Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
//     // Dashboard
//     Route::get('/dashboard', [AuthController::class, 'dashboard'], function () {
//         return response()->json([
//             'message' => 'Student Dashboard'
//         ]);
//     });
//     Route::get('/my-profile', function () {
//         return auth()->user();
//     });
// });