<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentClass;

class ClassRoomController extends Controller
{
    public function index(Request $request)
    {
        // 1. AUTH USER
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // 2. GET STUDENT
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        // 3. GET CLASSROOM
        $classroom = StudentClass::with([
            'academicYear',
            'semester',
            'department',
            'schedules.course'
        ])
        ->where('id', $student->class_id)
        ->first();

        if (!$classroom) {
            return response()->json([
                'success' => false,
                'message' => 'Classroom not found'
            ], 404);
        }

        // 4. RESPONSE
        return response()->json([
            'success' => true,
            'message' => 'Classroom fetched successfully',

            // STUDENT INFO
            'student' => [
                'student_code' => $student->student_code,
                'name' => trim($student->first_name_english . ' ' . $student->last_name_english),
                'email' => $student->email,
            ],

            // CLASS INFO
            'data' => [
                'class_name' => $classroom->class_name,
                'room' => $classroom->room,
                'student_count' => $classroom->students->count(),

                'academic_year' => $classroom->academicYear?->academic_year,

                'semester' => $classroom->semester?->semester_name,

                'department' => $classroom->department?->department_name_english,

                // COURSES LIST
                'courses' => $classroom->schedules->map(function ($schedule) {
                    return [
                        'course_name' => $schedule->course?->course_name,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                    ];
                }),
            ]
        ]);
    }
}