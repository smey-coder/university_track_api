<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AssignmentSubmissionApiController;
use App\Http\Controllers\Api\AssignmentController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Flutter LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard']);

    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | DEPARTMENTS
    |--------------------------------------------------------------------------
    */
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::post('/departments', [DepartmentController::class, 'store']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::put('/departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'show']);


    //Assignment
    Route::get('/assignments', [AssignmentController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT SUBMISSIONS (NEW 🚀)
    |--------------------------------------------------------------------------
    */

    Route::get('/submissions', [AssignmentSubmissionApiController::class, 'index']);
    Route::post('/submissions', [AssignmentSubmissionApiController::class, 'store']);
    Route::get('/submissions/{id}', [AssignmentSubmissionApiController::class, 'show']);
});

//For test
//-----------------------------------------------------------------
// Route::get('/assignments', [AssignmentController::class, 'index']);