<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\SubjectSchedule;

class ClassroomController extends Controller
{

    /**
     * ==========================================
     * Classroom List
     * ==========================================
     */
    public function index(Request $request)
    {
        try {

            $query = StudentClass::with([
                'department',
                'academicYear',
                'classSemesters.semester',
                'classSemesters.academicYear',
                'students',
                'schedules.teacher',
                'schedules.course',
                'schedules.academicYear',
            ])
            ->withCount('students');


            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            if ($request->filled('search')) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'class_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'room',
                        'like',
                        "%{$search}%"
                    );

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            */

            if ($request->filled('department_id')) {

                $query->where(
                    'department_id',
                    $request->department_id
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Academic Year
            |--------------------------------------------------------------------------
            */

            if ($request->filled('academic_year_id')) {

                $query->where(
                    'academic_year_id',
                    $request->academic_year_id
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Semester
            |--------------------------------------------------------------------------
            */

            if ($request->filled('semester_id')) {

                $query->whereHas(
                    'classSemesters',
                    function ($q) use ($request) {

                        $q->where(
                            'semester_id',
                            $request->semester_id
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            if ($request->filled('status')) {

                $query->where(
                    'status',
                    $request->status
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Room
            |--------------------------------------------------------------------------
            */

            if ($request->filled('room')) {

                $query->where(
                    'room',
                    $request->room
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Teacher
            |--------------------------------------------------------------------------
            */

            if ($request->filled('teacher_id')) {

                $query->whereHas(
                    'schedules',
                    function ($q) use ($request) {

                        $q->where(
                            'teacher_id',
                            $request->teacher_id
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Course
            |--------------------------------------------------------------------------
            */

            if ($request->filled('course_id')) {

                $query->whereHas(
                    'schedules',
                    function ($q) use ($request) {

                        $q->where(
                            'course_id',
                            $request->course_id
                        );

                    }
                );

            }



            $classrooms = $query
                ->latest()
                ->paginate(
                    $request->per_page ?? 10
                );



            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            $classrooms->getCollection()->transform(function ($class) {

                $class->courses_count =
                    $class->schedules
                        ->pluck('course_id')
                        ->unique()
                        ->count();

                $class->teachers_count =
                    $class->schedules
                        ->pluck('teacher_id')
                        ->unique()
                        ->count();

                return $class;

            });



            return response()->json([

                'success' => true,

                'message' =>
                'Classroom list loaded successfully.',

                'data' => $classrooms

            ]);



        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' =>
                'Failed to load classrooms.',

                'error' =>
                $e->getMessage()

            ],500);

        }
    }



    /**
     * ==========================================
     * Classroom Details
     * ==========================================
     */
    public function show($id)
    {
        try {

            $class = StudentClass::with([

                'department',

                'academicYear',

                'classSemesters.semester',

                'classSemesters.academicYear',

                'students.department',

                'schedules.teacher',

                'schedules.course',

                'schedules.academicYear'

            ])
            ->withCount('students')
            ->find($id);



            if (!$class) {

                return response()->json([

                    'success' => false,

                    'message' => 'Classroom not found.'

                ],404);

            }



            return response()->json([

                'success'=>true,

                'data'=>$class

            ]);



        } catch (\Exception $e) {

            return response()->json([

                'success'=>false,

                'message'=>'Failed to load classroom.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
        /**
     * ==========================================
     * Students In Classroom
     * ==========================================
     */
    public function students($id)
    {
        try {

            $students = Student::with([
                'department',
                'studentClass'
            ])
            ->where('class_id', $id)
            ->orderBy('student_code')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $students
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to load students.',
                'error' => $e->getMessage()
            ], 500);

        }
    }


    /**
     * ==========================================
     * Teachers In Classroom
     * ==========================================
     */
    public function teachers($id)
    {
        try {

            $teachers = SubjectSchedule::with([
                'teacher',
                'academicYear'
            ])
            ->where('class_id', $id)
            ->get()
            ->pluck('teacher')
            ->unique('id')
            ->values();

            return response()->json([
                'success' => true,
                'data' => $teachers
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to load teachers.',
                'error' => $e->getMessage()
            ], 500);

        }
    }


    /**
     * ==========================================
     * Courses In Classroom
     * ==========================================
     */
    public function courses($id)
    {
        try {

            $classroom = StudentClass::with([

                'classSemesters.semester',

                'classSemesters.academicYear',

                'schedules.teacher',

                'schedules.course',

                'schedules.academicYear'

            ])->findOrFail($id);


            $courses = $classroom->schedules->map(function ($schedule) {

                return [

                    'schedule_id' => $schedule->id,

                    'academic_year' =>
                        $schedule->academicYear?->academic_year,

                    'course' => [

                        'id' => $schedule->course?->id,

                        'course_code' =>
                            $schedule->course?->course_code,

                        'course_name' =>
                            $schedule->course?->course_name,

                        'credits' =>
                            $schedule->course?->credits,

                    ],

                    'teacher' => [

                        'id' =>
                            $schedule->teacher?->id,

                        'teacher_code' =>
                            $schedule->teacher?->teacher_code,

                        'full_name_english' =>
                            $schedule->teacher?->full_name_english,

                    ],

                    'day_of_week' =>
                        $schedule->day_of_week,

                    'start_time' =>
                        $schedule->start_time,

                    'end_time' =>
                        $schedule->end_time,

                    'room' =>
                        $schedule->room,

                    'status' =>
                        $schedule->status

                ];

            });


            return response()->json([

                'success' => true,

                'data' => $courses

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => 'Failed to load courses.',

                'error' => $e->getMessage()

            ], 500);

        }
    }


    /**
     * ==========================================
     * Classroom Schedule
     * ==========================================
     */
    public function schedule($id)
    {
        try {

            $schedule = SubjectSchedule::with([

                'teacher',

                'course',

                'academicYear'

            ])
            ->where('class_id', $id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

            return response()->json([

                'success' => true,

                'data' => $schedule

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => 'Failed to load schedule.',

                'error' => $e->getMessage()

            ], 500);

        }
    }
}