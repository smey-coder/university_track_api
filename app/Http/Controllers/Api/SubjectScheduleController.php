<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubjectSchedule;
use App\Models\Student;
use App\Models\StudentClass;
use Carbon\Carbon;

class SubjectScheduleController extends Controller
{
    /**
     * Student Timetable API
     */
    public function index(Request $request)
    {
        try {

            // ================= AUTH USER =================
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated user.'
                ], 401);
            }

            // ================= STUDENT =================
            $student = Student::with([
                'classes',
                'semester',
                'department'
            ])->where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

            // ================= SCHEDULES =================
            $schedules = SubjectSchedule::with([
                    'course',
                    'class',
                    'semester',
                    'teacher'
                ])
                ->where('class_id', $student->class_id)
                ->where('status', 'active')
                ->orderByRaw("
                    CASE day_of_week
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                        WHEN 'Saturday' THEN 6
                        WHEN 'Sunday' THEN 7
                        ELSE 8
                    END
                ")
                ->orderBy('start_time')
                ->get();

            // ================= RESPONSE =================
            return response()->json([
                'success' => true,
                'message' => 'Schedule retrieved successfully.',

                'student' => [
                    'id' => $student->id,
                    'class_name' => $student->classes?->class_name ?? '-',
                    'semester_name' => $student->semester?->semester_name ?? '-',
                    'department' => $student->department?->department_name_english ?? '-',
                ],

                'summary' => [
                    'total_subjects' => $schedules->count(),
                    'today' => Carbon::now()->format('l'),
                ],

                'data' => $schedules

            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to load schedule.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}