<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentClass;

class StudentClassroomController extends Controller
{
    public function index(Request $request)
    {
        try {

            $user = auth()->user();

            if(!$user){
                return response()->json([
                    'success'=>false,
                    'message'=>'Unauthenticated'
                ],401);
            }


            $student = $user->student;


            if(!$student){

                return response()->json([
                    'success'=>false,
                    'message'=>'Student profile not found'
                ],404);

            }


            $academicYearId = $request->academic_year_id;
            $semesterId = $request->semester_id;



            $classroom = StudentClass::with([

                'department',

                'academicYear',

                'classSemesters.semester',

                'classSemesters.academicYear',


                'schedules' => function($query) use(
                    $academicYearId,
                    $semesterId
                ){

                    if($academicYearId){

                        $query->where(
                            'academic_year_id',
                            $academicYearId
                        );

                    }


                    if($semesterId){

                        $query->where(
                            'semester_id',
                            $semesterId
                        );

                    }


                },


                'schedules.course',

                'schedules.teacher',

            ])
            ->where('id',$student->class_id)



            // FILTER CLASS SEMESTER
            ->whereHas('classSemesters',function($query) use(
                $academicYearId,
                $semesterId
            ){

                if($academicYearId){

                    $query->where(
                        'academic_year_id',
                        $academicYearId
                    );

                }


                if($semesterId){

                    $query->where(
                        'semester_id',
                        $semesterId
                    );

                }

            })

            ->first();



            if(!$classroom){

                return response()->json([
                    'success'=>false,
                    'message'=>'Classroom not found'
                ],404);

            }



            return response()->json([

                'success'=>true,

                'data'=>$classroom

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }
    }
}