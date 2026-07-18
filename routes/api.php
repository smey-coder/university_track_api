<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Mobile app API
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
//Web App API
use App\Http\Controllers\Api\Web_api\AuthController as WebApiAuthController;
use App\Http\Controllers\Api\Web_api\StudentController as WebApiStudentController;
use App\Http\Controllers\Api\Web_api\TeacherController as WebApiTeacherController;
use App\Http\Controllers\Api\Web_api\DashboardController as WebApiDashboardController;
use App\Http\Controllers\Api\Web_api\DepartmentController as WebApiDepartmentController;
use App\Http\Controllers\Api\Web_api\RoleController as WebApiRoleController;
use App\Http\Controllers\Api\Web_api\PermissionController as WebApiPermissionController;
use App\Http\Controllers\Api\Web_api\RolePermissionController as WebApiRolePermissionController;
use App\Http\Controllers\Api\Web_api\UserRoleController as WebApiUserRoleController;
use App\Http\Controllers\Api\Web_api\UserController as WebApiUserController;
use App\Http\Controllers\Api\Web_api\CourseController as WebApiCourseController;
use App\Http\Controllers\Api\Web_api\AssignmentController as WebApiAssignmentController;
use App\Http\Controllers\Api\Web_api\AssignmentSubmissionController as WebApiAssignmentSubmissionController;
use App\Http\Controllers\Api\Web_api\ChatController as WebApiChatController;
use App\Http\Controllers\Api\Web_api\ClassManagerController as WebApiClassManagerController;
use App\Http\Controllers\Api\Web_api\ClassController as WebApiClassController;

use App\Http\Controllers\Api\Web_api\AcademicYearController as WebApiAcademicYearController;
use App\Http\Controllers\Api\Web_api\SemesterController as WebApiSemesterController;
use App\Http\Controllers\Api\Web_api\ClassroomController as WebApiClassroomController;
use App\Http\Controllers\Api\Web_api\SubjectScheduleController as WebApiSubjectScheduleController;
use App\Http\Controllers\Api\Web_api\StudentClassroomController as WebApiStudentClassroomController;


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
    //Public for test
    // Route::get('/courses', [WebApiCourseController::class, 'index']);
    // Route::get('/get-form-data-dependencies', [WebApiCourseController::class, 'getFormDataDependencies']);
    // Route::post('/courses/create', [WebApiCourseController::class, 'store']);
    // Route::put('/courses/update/{id}', [WebApiCourseController::class, 'update']);
    // Route::delete('/courses/delete/{id}', [WebApiCourseController::class, 'destroy']);

    // Route::prefix('/assignment-submissions')->group(function () {
    //         Route::get('/', [WebApiAssignmentSubmissionController::class,'index']);
    //         Route::post('/create', [WebApiAssignmentSubmissionController::class,'store']);
    //         Route::get('/show/{id}', [WebApiAssignmentSubmissionController::class,'show']);
    //         Route::put('/{id}/grade', [WebApiAssignmentSubmissionController::class,'grade']);
    //         Route::delete('/delete/{id}', [WebApiAssignmentSubmissionController::class,'destroy']);

    //     });
    
    // Public Auth
    Route::post('/register', [WebApiAuthController::class, 'register']); 
    Route::post('/activate/student', [WebApiAuthController::class,'activateStudent']);
    Route::post('/activate/teacher',[WebApiAuthController::class,'activateTeacher']);
    Route::post('/login', [WebApiAuthController::class, 'login'])->name('web.login'); 

    //Dropdown
    Route::get('/departments/dropdown', [WebApiDepartmentController::class, 'dropdown']);
    Route::get('/semesters/dropdown',[WebApiSemesterController::class,'dropdown']);
    Route::get('/academic-years/dropdown',[WebApiAcademicYearController::class,'dropdown']);

    //Test
    // Route::apiResource('/permissions', WebApiPermissionController::class);
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () { 
        // Route::get('/user', function (Request $request) {
        //     return $request->user();
        // });
        // Route::get('/user', function (Request $request) {
        //     $user = $request->user();
        //     return response()->json([
        //         "id" => $user->id,
        //         "username" => $user->username,
        //         "email" => $user->email,
        //         // Important
        //         "role" => strtolower(
        //             $user->getRoleNames()->first()
        //         ),
        //         "roles" => $user->getRoleNames(),
        //         "permissions" =>
        //             $user->getAllPermissions()
        //             ->pluck('name')
        //             ->values()
        //     ]);
        // });
        Route::post('/chat',[WebApiChatController::class,'chat']);

        Route::get('/user', function (Request $request) {
            $user = $request->user()->load([
                'student.department',
                'student.classes',
                'student.semester',
                'teacher.department'
            ]);
            $photo = null;
            $fullName = $user->username;
            if ($user->student) {

                $photo = $user->student->photo
                    ? asset('storage/'.$user->student->photo)
                    : null;

                $fullName =
                    $user->student->first_name_english.' '.
                    $user->student->last_name_english;
            }

            if ($user->teacher) {

                $photo = $user->teacher->photo
                    ? asset('storage/'.$user->teacher->photo)
                    : $photo;

                $fullName =
                    $user->teacher->first_name_english.' '.
                    $user->teacher->last_name_english;
            }

            return response()->json([

                "id"=>$user->id,

                "username"=>$user->username,

                "full_name"=>$fullName,

                "email"=>$user->email,

                "photo"=>$photo,

                "role"=>strtolower(
                    $user->getRoleNames()->first()
                ),

                "roles"=>$user->getRoleNames(),

                "permissions"=>$user
                    ->getAllPermissions()
                    ->pluck('name')
                    ->values(),

                "student"=>$user->student,
                "teacher"=>$user->teacher

            ]);

        });

        //RBAC
        // User List + Show
        Route::get(
            '/users',
            [WebApiUserController::class,'index']
        )
        ->middleware('permission:user.view');


        Route::get(
            '/users/{id}',
            [WebApiUserController::class,'show']
        )
        ->middleware('permission:user.view');
        // Create User
        Route::post(
            '/users',
            [WebApiUserController::class,'store']
        )
        ->middleware('permission:user.create');
        // Update User
        Route::put(
            '/users/{id}',
            [WebApiUserController::class,'update']
        )
        ->middleware('permission:user.update');



        // Delete User
        Route::delete(
            '/users/{id}',
            [WebApiUserController::class,'destroy']
        )
        ->middleware('permission:user.delete');

        // Role dropdown
        Route::get(
            '/user-roles',
            [WebApiUserController::class,'roles']
        )
        ->middleware('permission:user.create|user.update');

        Route::apiResource('/roles', WebApiRoleController::class);
        Route::apiResource('/permissions', WebApiPermissionController::class);

        // Get role permissions
        Route::get(
            '/roles/{roleId}/permissions',
            [WebApiRolePermissionController::class,'show']
        );
        // Assign permissions
        Route::post(
            '/roles/{roleId}/permissions',
            [WebApiRolePermissionController::class,'store']
        );
        // Remove permission
        Route::delete(
            '/roles/{roleId}/permissions',
            [WebApiRolePermissionController::class,'destroy']
        );
        //--------------------------------------------------
        // Get user roles
        Route::get(
            '/users/{userId}/roles',
            [WebApiUserRoleController::class,'show']
        );
        // Assign roles
        Route::post(
            '/users/{userId}/roles',
            [WebApiUserRoleController::class,'store']
        );
        // Remove role
        Route::delete(
            '/users/{userId}/roles',
            [WebApiUserRoleController::class,'destroy']
        );

        // Route::get('/dashboard', [WebApiAuthController::class, 'dashboard']); 
        Route::post('/logout', [WebApiAuthController::class, 'logout'])->name('web.logout'); 

        // Dropdowns dictionary metadata lookup endpoint
        Route::get('/students/form-dependencies', [WebApiStudentController::class, 'getFormDataDependencies']);
        Route::get('/teachers/form-dependencies', [WebApiTeacherController::class, 'getFormDataDependencies']);
        
        // Core CRUD resource mapping endpoints
        Route::get('/students', [WebApiStudentController::class, 'index'])
            ->middleware('permission:student.view');

        Route::post('/students', [WebApiStudentController::class, 'store'])
            ->middleware('permission:student.create');

        Route::get('/students/{student}', [WebApiStudentController::class, 'show'])
            ->middleware('permission:student.view');

        Route::put('/students/{student}', [WebApiStudentController::class, 'update'])
            ->middleware('permission:student.update');

        Route::delete('/students/{student}', [WebApiStudentController::class, 'destroy'])
            ->middleware('permission:student.delete');

        // Route::apiResource('teachers', WebApiTeacherController::class);

        //Teacher
        // Route::get('/teachers', [WebApiTeacherController::class, 'index']);
        // Route::post('/teachers', [WebApiTeacherController::class, 'store']);
        // Route::get('/teachers/{id}', [WebApiTeacherController::class, 'show']);
        // Route::put('/teachers/{id}', [WebApiTeacherController::class, 'update']);
        // Route::delete('/teachers/{id}', [WebApiTeacherController::class, 'destroy']);
        Route::get('/teachers', [WebApiTeacherController::class, 'index'])
            ->middleware('permission:teacher.view');

        Route::post('/teachers', [WebApiTeacherController::class, 'store'])
            ->middleware('permission:teacher.create');

        // Put dropdown BEFORE {id}
        Route::get('/teachers/dropdown', [WebApiTeacherController::class, 'dropdown'])
            ->middleware('permission:teacher.view');

        Route::get('/teachers/{id}', [WebApiTeacherController::class, 'show'])
            ->middleware('permission:teacher.view');

        Route::put('/teachers/{id}', [WebApiTeacherController::class, 'update'])
            ->middleware('permission:teacher.update');

        Route::delete('/teachers/{id}', [WebApiTeacherController::class, 'destroy'])
            ->middleware('permission:teacher.delete');

        //Dashboard
        Route::get('/dashboards',[WebApiDashboardController::class, 'index'])->middleware('permission:dashboard.view');

        Route::post('/dashboards', [WebApiDashboardController::class, 'store']);
        Route::get('/dashboards/{id}', [WebApiDashboardController::class, 'show']);
        Route::put('/dashboards/{id}', [WebApiDashboardController::class, 'update']);
        Route::delete('/dashboards/{id}', [WebApiDashboardController::class, 'destroy']);

        //Department
        // Route::get('/departments', [WebApiDepartmentController::class, 'index']);
        // Route::post('/departments', [WebApiDepartmentController::class, 'store']);
        // Route::get('/departments/{id}', [WebApiDepartmentController::class, 'show']);
        // Route::put('/departments/{id}', [WebApiDepartmentController::class, 'update']);
        // Route::delete('/departments/{id}', [WebApiDepartmentController::class, 'destroy']);

        // Route::get(
        //     '/departments/dropdown',
        //     [WebApiDepartmentController::class, 'dropdown']
        // )->middleware('permission:department.view');
        
        Route::get(
            '/departments',
            [WebApiDepartmentController::class, 'index']
        )->middleware('permission:department.view');

        Route::post(
            '/departments',
            [WebApiDepartmentController::class, 'store']
        )->middleware('permission:department.create');

        Route::get(
            '/departments/{id}',
            [WebApiDepartmentController::class, 'show']
        )->middleware('permission:department.view');

        Route::put(
            '/departments/{id}',
            [WebApiDepartmentController::class, 'update']
        )->middleware('permission:department.update');

        Route::delete(
            '/departments/{id}',
            [WebApiDepartmentController::class, 'destroy']
        )->middleware('permission:department.delete');

        Route::prefix('/courses')->group(function () {
            Route::get('/', [WebApiCourseController::class, 'index']);
            Route::get('/create-data', [WebApiCourseController::class, 'getFormDataDependencies']);
            Route::post('/create', [WebApiCourseController::class, 'store']);
            Route::get('/show/{id}', [WebApiCourseController::class, 'show']);
            Route::put('/update/{id}', [WebApiCourseController::class, 'update']);
            Route::delete('/delete/{id}', [WebApiCourseController::class, 'destroy']);
            Route::get('/dropdown', [WebApiCourseController::class, 'dropdown']);
        });

        Route::prefix('/assignments')->group(function(){
            Route::get('/',[WebApiAssignmentController::class,'index']);
            Route::get('/form-data',[WebApiAssignmentController::class,'getFormDataDependencies']);
            Route::post('/create',[WebApiAssignmentController::class,'store']);
            Route::get('/show/{id}',[WebApiAssignmentController::class,'show']);
            Route::put('/update/{id}',[WebApiAssignmentController::class,'update']);
            Route::delete('/delete/{id}',[WebApiAssignmentController::class,'destroy']);
        });

        //Assignment Submissions

        Route::prefix('/assignment-submissions')->group(function () {
            Route::get('/', [WebApiAssignmentSubmissionController::class,'index']);
            Route::get('/available',[WebApiAssignmentSubmissionController::class,'available']);
            Route::post('/create', [WebApiAssignmentSubmissionController::class,'store']);
            Route::get('/show/{id}', [WebApiAssignmentSubmissionController::class,'show']);
            Route::put('/{id}/grade', [WebApiAssignmentSubmissionController::class,'grade']);
            Route::delete('/delete/{id}', [WebApiAssignmentSubmissionController::class,'destroy']);

        });
       Route::prefix('class-managers')->group(function () {

            // Get all class assignments
            Route::get(
                '/',
                [WebApiClassManagerController::class,'index']
            );

            // Assign student to class
            Route::post(
                '/create',
                [WebApiClassManagerController::class,'store']
            );

            // Show assignment detail
            Route::get(
                '/show/{id}',
                [WebApiClassManagerController::class,'show']
            );

            // Update class assignment
            Route::put(
                '/update/{id}',
                [WebApiClassManagerController::class,'update']
            );
            // Delete assignment
            Route::delete(
                '/delete/{id}',
                [WebApiClassManagerController::class,'destroy']
            );
            // Get students by class
            // Example: MS4 students
            Route::get(
                '/classes/{classId}/students',
                [WebApiClassManagerController::class,'studentsByClass']
            );

            // Student class history
            Route::get(
                '/student/{studentId}/history',
                [WebApiClassManagerController::class,'studentHistory']
            );
            // Current logged-in student class
            Route::get(
                '/my-class',
                [WebApiClassManagerController::class,'myClass']
            );
            // Students without class
            Route::get(
                '/available-students',
                [WebApiClassManagerController::class,'availableStudents']
            );
        });

            Route::prefix('classes')->group(function () {

            Route::get('/', [WebApiClassController::class, 'index']);
            Route::post('/', [WebApiClassController::class, 'store']);
            Route::get('/dropdown', [WebApiClassController::class, 'dropdown']);
            Route::get('/available', [WebApiClassController::class, 'availableClasses']);
            Route::get('/{id}', [WebApiClassController::class, 'show']);
            Route::put('/{id}', [WebApiClassController::class, 'update']);
            Route::delete('/{id}', [WebApiClassController::class, 'destroy']);
            Route::get('/{id}/students',[WebApiClassController::class,'students']);
            Route::get('/{id}/statistics',[WebApiClassController::class,'statistics']);
            Route::post('/promote',[WebApiClassController::class,'promoteStudents']);
        });
        Route::prefix('classrooms')->group(function () {

            Route::get('/', [WebApiClassroomController::class, 'index']);
            Route::get('/statistics', [WebApiClassroomController::class, 'statistics']);
            Route::get('/{id}', [WebApiClassroomController::class, 'show']);
            Route::get('/{id}/students', [WebApiClassroomController::class, 'students']);
            Route::get('/{id}/teachers', [WebApiClassroomController::class, 'teachers']);
            Route::get('/{id}/courses', [WebApiClassroomController::class, 'courses']);
            Route::get('/{id}/schedule', [WebApiClassroomController::class, 'schedule']);

        });

        Route::apiResource('subject-schedules',WebApiSubjectScheduleController::class);
        Route::get('classrooms/{id}/schedule',[WebApiSubjectScheduleController::class,'classroomSchedule']);
        Route::get('teachers/{id}/schedule',[WebApiSubjectScheduleController::class,'teacherSchedule']);

        Route::get('/student/classroom',[WebApiStudentClassroomController::class,'index']);
    }); 
    

    
});



//For test
//-----------------------------------------------------------------

// Route::get('/departments', [DepartmentController::class, 'index']);
// Route::post('/departments', [DepartmentController::class, 'store']);
// Route::get('/departments/{id}', [DepartmentController::class, 'show']);
// Route::put('/departments/{id}', [DepartmentController::class, 'update']);
// Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);