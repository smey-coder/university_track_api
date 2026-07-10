<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\ClassManagerController;
use App\Http\Controllers\SubjectScheduleController;
use App\Http\Controllers\AttendanceSessionController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.index');
    Route::resource('students', StudentController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('class-managers', ClassManagerController::class);
    Route::resource('subject-schedules', SubjectScheduleController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('assignments', AssignmentController::class);
    Route::resource('assignment_submissions', AssignmentSubmissionController::class);
    Route::resource('attendance_sessions', AttendanceSessionController::class);
    // custom routes
    Route::post('attendance_sessions/{id}/close', [AttendanceSessionController::class, 'close'])
        ->name('attendance_sessions.close');

    Route::post('attendance_sessions/{id}/regenerate', [AttendanceSessionController::class, 'regenerateCode'])
        ->name('attendance_sessions.regenerate');

    /// =============================
    // Attendance CRUD
    // =============================
    Route::resource('attendance_records', AttendanceController::class);

    // =============================
    // MANUAL CODE SCAN
    // =============================
    Route::get('/attendance/scan', [AttendanceController::class, 'scanForm'])
        ->name('attendances.scan');

    Route::post('/attendance/scan', [AttendanceController::class, 'processScan'])
        ->name('attendances.scan.process');

    // =============================
    // QR SCAN PAGE (NEW UI PAGE)
    // =============================
    Route::get('/attendance/scan-qr', [AttendanceController::class, 'scanQRPage'])
        ->name('attendances.scan.qr.page');

    Route::post('/attendance/scan-qr', [AttendanceController::class, 'processQRScan'])
        ->name('attendances.scan.qr.process');
});

require __DIR__.'/auth.php';
