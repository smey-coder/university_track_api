<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AssignmentSubmissionController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\SubjectScheduleController;
use App\Http\Controllers\Api\TodayScheduleController;
use App\Http\Controllers\Api\ClassRoomController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\AttendanceRecordController;
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
    | ASSIGNMENT SUBMISSIONS (NEW)
    |--------------------------------------------------------------------------
    */

     Route::get('/assignment-submissions', 
        [AssignmentSubmissionController::class, 'index']
    );

    //Get single submission
    Route::get('/assignment-submissions/{id}', 
        [AssignmentSubmissionController::class, 'show']
    );

    // Submit assignment (student only)
    Route::post('/assignment-submissions', 
        [AssignmentSubmissionController::class, 'store']
    );

    Route::get('/subject-schedules',
        [SubjectScheduleController::class, 'index']
    );
    Route::get('/today-schedules',[TodayScheduleController::class, 'index']);
    Route::get('/class-room',[ClassRoomController::class, 'index']);
    Route::get('/profile',[SettingController::class, 'profile']);
    Route::put('/profile/update',[SettingController::class, 'updateProfile']);
    Route::post('/change-password',[SettingController::class, 'changePassword']);
    Route::post('/logout',[SettingController::class, 'logout']);

    //Attendance Recorde
    Route::get('/attendance', [AttendanceRecordController::class, 'index']);
    Route::post('/attendance/create', [AttendanceRecordController::class, 'scan']);
    Route::get('/attendance/summary',[AttendanceRecordController::class, 'summary']);
    Route::get('/attendance/subjects',[AttendanceRecordController::class, 'subjectAttendance']);
    Route::get('/attendance/courseSummary',[AttendanceRecordController::class, 'courseSummary']);

});

//For test
//-----------------------------------------------------------------
// Route::get('/assignments', [AssignmentController::class, 'index']);

//  Route::get('/subject-schedules',
//         [SubjectScheduleController::class, 'index']
//     );

Route::get('/departments', [DepartmentController::class, 'index']);
Route::post('/departments', [DepartmentController::class, 'store']);
Route::get('/departments/{id}', [DepartmentController::class, 'show']);
Route::put('/departments/{id}', [DepartmentController::class, 'update']);
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);