<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SubjectSchedule;

class TodayScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated user'
            ], 401);
        }

        $student = Student::with([
            'department',
            'classes',
            'semester'
        ])->where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $today = now()->format('l');

        $schedules = SubjectSchedule::with([
                'course',
                'teacher',
                'semester'
            ])
            ->where('class_id', $student->class_id)
            ->where('day_of_week', $today)
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'today' => $today,
            'student' => [
                'student_code' => $student->student_code,
                'class_name' => $student->classes?->class_name,
                'semester_name' => $student->semester?->semester_name,
                'department' => $student->department?->department_name_english,
            ],
            'total_subjects' => $schedules->count(),
            'data' => $schedules
        ]);
    }
}
