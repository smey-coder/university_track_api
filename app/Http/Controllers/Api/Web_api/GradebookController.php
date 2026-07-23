<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Gradebook;
use App\Models\Course;
use App\Models\Student;


class GradebookController extends Controller
{


    /**
     * =====================================
     * View Gradebook By Course
     * =====================================
     */
    public function courseGradebook($courseId)
    {

        try {


            $course = Course::find($courseId);


            if(!$course){

                return response()->json([

                    'success'=>false,

                    'message'=>'Course not found.'

                ],404);

            }



            $gradebooks = Gradebook::with([

                'student',

                'course'

            ])
            ->where(
                'course_id',
                $courseId
            )
            ->get();



            return response()->json([

                'success'=>true,

                'course'=>$course->course_name,

                'data'=>$gradebooks

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }




    /**
     * =====================================
     * Student Grade Detail
     * =====================================
     */
    public function studentGrade(
        $courseId,
        $studentId
    )
    {

        try {


            $grade = Gradebook::with([

                'course',

                'student'

            ])
            ->where(
                'course_id',
                $courseId
            )
            ->where(
                'student_id',
                $studentId
            )
            ->first();



            if(!$grade){

                return response()->json([

                    'success'=>false,

                    'message'=>'Grade not found.'

                ],404);

            }



            return response()->json([

                'success'=>true,

                'data'=>$grade

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }




    /**
     * =====================================
     * Save / Update Grade
     * =====================================
     */
    public function store(Request $request)
    {

        try {


            $request->validate([

                'course_id'=>
                'required|exists:courses,id',

                'student_id'=>
                'required|exists:students,id',

                'final_score'=>
                'required|numeric|min:0|max:100'

            ]);



            // Convert Letter

            $letter = $this->calculateLetter(
                $request->final_score
            );


            // Convert GPA

            $gpa = $this->calculateGPA(
                $request->final_score
            );



            $grade = Gradebook::updateOrCreate(

                [

                    'course_id'=>
                    $request->course_id,


                    'student_id'=>
                    $request->student_id

                ],


                [

                    'final_score'=>
                    $request->final_score,


                    'letter_grade'=>
                    $letter,


                    'gpa'=>
                    $gpa

                ]

            );



            return response()->json([

                'success'=>true,

                'message'=>
                'Grade saved successfully.',

                'data'=>$grade

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }




    /**
     * Letter Grade
     */
    private function calculateLetter($score)
    {

        if($score >= 90)
            return "A";


        if($score >= 85)
            return "B+";


        if($score >= 80)
            return "B";


        if($score >= 70)
            return "C";


        if($score >= 60)
            return "D";


        return "F";

    }




    /**
     * GPA
     */
    private function calculateGPA($score)
    {

        if($score >= 90)
            return 4.0;


        if($score >= 85)
            return 3.5;


        if($score >= 80)
            return 3.0;


        if($score >= 70)
            return 2.5;


        if($score >= 60)
            return 2.0;


        return 0;

    }


}