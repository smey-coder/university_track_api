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
//WebAPI
use App\Http\Controllers\Api\Web_api\AuthController as WebApiAuthController;
use App\Http\Controllers\Api\Web_api\StudentController as WebApiStudentController;
use App\Http\Controllers\Api\Web_api\TeacherController as WebApiTeacherController;
use App\Http\Controllers\Api\Web_api\DashboardController as WebApiDashboardController;

/*
|--------------------------------------------------------------------------
| FLUTTER MOBILE API ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('mobile')->group(function () {
    // Departments (Using resource routing shorthand for cleaner code)
    Route::apiResource('departments', DepartmentController::class);

    // Public Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('mobile.login');

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        
        Route::get('/dashboard', [AuthController::class, 'dashboard']);
        Route::post('/logout', [SettingController::class, 'logout']); // Unified logout

        // Students
        Route::get('/students', [StudentController::class, 'index']);
        Route::get('/students/{id}', [StudentController::class, 'show']);

        // Assignments & Submissions
        Route::get('/assignments', [AssignmentController::class, 'index']);
        Route::get('/assignment-submissions', [AssignmentSubmissionController::class, 'index']);
        Route::get('/assignment-submissions/{id}', [AssignmentSubmissionController::class, 'show']);
        Route::post('/assignment-submissions', [AssignmentSubmissionController::class, 'store']);

        // Schedules & Classroom
        Route::get('/subject-schedules', [SubjectScheduleController::class, 'index']);
        Route::get('/today-schedules', [TodayScheduleController::class, 'index']);
        Route::get('/class-room', [ClassRoomController::class, 'index']);
        
        // Profile & Settings
        Route::get('/profile', [SettingController::class, 'profile']);
        Route::put('/profile/update', [SettingController::class, 'updateProfile']);
        Route::post('/change-password', [SettingController::class, 'changePassword']);

        // Attendance Records
        Route::get('/attendance', [AttendanceRecordController::class, 'index']);
        Route::post('/attendance/create', [AttendanceRecordController::class, 'scan']);
        Route::get('/attendance/summary', [AttendanceRecordController::class, 'summary']);
        Route::get('/attendance/subjects', [AttendanceRecordController::class, 'subjectAttendance']);
        Route::get('/attendance/courseSummary', [AttendanceRecordController::class, 'courseSummary']);
    });
});

/*
|--------------------------------------------------------------------------
| WEB API ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('web')->group(function () {
    Route::apiResource('departments', DepartmentController::class);
    // Public Auth
    Route::post('/register', [WebApiAuthController::class, 'register']); 
    Route::post('/login', [WebApiAuthController::class, 'login'])->name('web.login'); 
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () { 
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('/dashboard', [WebApiAuthController::class, 'dashboard']); 
        Route::post('/logout', [WebApiAuthController::class, 'logout'])->name('web.logout'); 

        // Dropdowns dictionary metadata lookup endpoint
        Route::get('/students/form-dependencies', [WebApiStudentController::class, 'getFormDataDependencies']);
        Route::get('/teachers/form-dependencies', [WebApiTeacherController::class, 'getFormDataDependencies']);
        
        // Core CRUD resource mapping endpoints
        Route::apiResource('students', WebApiStudentController::class);

        // Route::apiResource('teachers', WebApiTeacherController::class);

        //Teacher
        Route::get('/teachers', [WebApiTeacherController::class, 'index']);
        Route::post('/teachers', [WebApiTeacherController::class, 'store']);
        Route::get('/teachers/{id}', [WebApiTeacherController::class, 'show']);
        Route::put('/teachers/{id}', [WebApiTeacherController::class, 'update']);
        Route::delete('/teachers/{id}', [WebApiTeacherController::class, 'destroy']);

        //Dashboard
        Route::get('/dashboards', [WebApiDashboardController::class, 'index']);
        Route::post('/dashboards', [WebApiDashboardController::class, 'store']);
        Route::get('/dashboards/{id}', [WebApiDashboardController::class, 'show']);
        Route::put('/dashboards/{id}', [WebApiDashboardController::class, 'update']);
        Route::delete('/dashboards/{id}', [WebApiDashboardController::class, 'destroy']);
    }); 
});


//For test
//-----------------------------------------------------------------

// Route::get('/departments', [DepartmentController::class, 'index']);
// Route::post('/departments', [DepartmentController::class, 'store']);
// Route::get('/departments/{id}', [DepartmentController::class, 'show']);
// Route::put('/departments/{id}', [DepartmentController::class, 'update']);
// Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);