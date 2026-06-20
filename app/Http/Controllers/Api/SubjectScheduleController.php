<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubjectSchedule;
use App\Models\Student;

class SubjectScheduleController extends Controller
{
    /**
     * STUDENT TIMETABLE API
     */
   public function index(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated user'
        ], 401);
    }

    $student = Student::where('user_id', $user->id)->first();

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'Student not found'
        ], 404);
    }

    $classId = $student->class_id;

    $schedules = SubjectSchedule::with(['course','class','semester','teacher'])
        ->where('class_id', $classId)
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->get();

    return response()->json([
        'success' => true,
        'info' => [
            'class_name' => $student->classes?->class_name ?? 'Not Available',
            'semester_name' => $student->semester?->semester_name ?? 'Not Available',
            'department' => $student->department?->department_name_english ?? 'Not Available',
        ],
        'data' => $schedules
    ]);
}
}