<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use App\Models\SubjectSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectScheduleController extends Controller
{

    /**
     * Display all schedules
     */
    public function index(Request $request)
    {
        try {

            $query = SubjectSchedule::with([
                'course',
                'class',
                'semester',
                'teacher',
                'academicYear'
            ]);


            // Filter Class
            if($request->filled('class_id')){

                $query->where(
                    'class_id',
                    $request->class_id
                );

            }


            // Filter Teacher
            if($request->filled('teacher_id')){

                $query->where(
                    'teacher_id',
                    $request->teacher_id
                );

            }


            // Filter Course
            if($request->filled('course_id')){

                $query->where(
                    'course_id',
                    $request->course_id
                );

            }


            // Filter Semester
            if($request->filled('semester_id')){

                $query->where(
                    'semester_id',
                    $request->semester_id
                );

            }


            $schedules = $query
                ->latest()
                ->paginate(
                    $request->per_page ?? 10
                );


            return response()->json([

                'success'=>true,

                'message'=>'Schedule loaded successfully',

                'data'=>$schedules

            ]);


        }catch(\Exception $e){


            Log::error(
                "Schedule Index Error : ".$e->getMessage()
            );


            return response()->json([

                'success'=>false,

                'message'=>'Failed to load schedule'

            ],500);

        }
    }



    /**
     * Store schedule
     */
    public function store(Request $request)
    {

        $validated = $request->validate([

            'course_id'
                =>'required|exists:courses,id',

            'class_id'
                =>'required|exists:classes,id',

            'semester_id'
                =>'required|exists:semesters,id',

            'academic_year_id'
                =>'required|exists:academic_years,id',

            'teacher_id'
                =>'required|exists:teachers,id',

            'day_of_week'
                =>'required|string',

            'start_time'
                =>'required',

            'end_time'
                =>'required',

            'room'
                =>'nullable|string',

            'status'
                =>'required|in:active,finished'

        ]);



        $schedule =
            SubjectSchedule::create($validated);



        return response()->json([

            'success'=>true,

            'message'=>'Schedule created successfully',

            'data'=>$schedule

        ],201);

    }





    /**
     * Show single schedule
     */
    public function show($id)
    {

        $schedule =
            SubjectSchedule::with([
                'course',
                'class',
                'semester',
                'teacher'
            ])
            ->find($id);



        if(!$schedule){

            return response()->json([

                'success'=>false,

                'message'=>'Schedule not found'

            ],404);

        }



        return response()->json([

            'success'=>true,

            'data'=>$schedule

        ]);

    }





    /**
     * Update schedule
     */
    public function update(Request $request,$id)
    {


        $schedule =
            SubjectSchedule::find($id);



        if(!$schedule){

            return response()->json([

                'success'=>false,

                'message'=>'Schedule not found'

            ],404);

        }



        $validated=$request->validate([


            'course_id'
                =>'sometimes|exists:courses,id',

            'class_id'
                =>'sometimes|exists:classes,id',

            'semester_id'
                =>'sometimes|exists:semesters,id',
            'academic_year_id'
                =>'required|exists:academic_years,id',

            'teacher_id'
                =>'sometimes|exists:teachers,id',

            'day_of_week'
                =>'sometimes|string',

            'start_time'
                =>'sometimes',

            'end_time'
                =>'sometimes',

            'room'
                =>'nullable|string',

            'status'
                =>'sometimes|in:active,finished'

        ]);



        $schedule->update($validated);



        return response()->json([

            'success'=>true,

            'message'=>'Schedule updated successfully',

            'data'=>$schedule

        ]);

    }





    /**
     * Delete schedule
     */
    public function destroy($id)
    {

        $schedule =
            SubjectSchedule::find($id);



        if(!$schedule){

            return response()->json([

                'success'=>false,

                'message'=>'Schedule not found'

            ],404);

        }



        $schedule->delete();



        return response()->json([

            'success'=>true,

            'message'=>'Schedule deleted successfully'

        ]);

    }





    /**
     * Get classroom timetable
     */
    public function classroomSchedule($classId)
    {

        $schedule =
            SubjectSchedule::with([
                'course',
                'teacher',
                'semester'
            ])
            ->where(
                'class_id',
                $classId
            )
            ->get();



        return response()->json([

            'success'=>true,

            'data'=>$schedule

        ]);

    }





    /**
     * Get teacher timetable
     */
    public function teacherSchedule($teacherId)
    {

        $schedule =
            SubjectSchedule::with([
                'course',
                'class',
                'semester'
            ])
            ->where(
                'teacher_id',
                $teacherId
            )
            ->get();



        return response()->json([

            'success'=>true,

            'data'=>$schedule

        ]);

    }


}