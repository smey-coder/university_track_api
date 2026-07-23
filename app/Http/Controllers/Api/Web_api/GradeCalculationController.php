<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Course;
use App\Models\AssignmentSubmission;


class GradeCalculationController extends Controller
{


    /**
     * ======================================
     * Calculate Student Final Grade
     * ======================================
     */
    public function studentGrade(
        $courseId,
        $studentId
    )
    {

        try {


            $student = Student::find($studentId);


            if(!$student){

                return response()->json([

                    'success'=>false,

                    'message'=>'Student not found.'

                ],404);

            }



            $course = Course::with([

                'gradeCategories',

                'assignments.gradeCategory'

            ])
            ->find($courseId);



            if(!$course){

                return response()->json([

                    'success'=>false,

                    'message'=>'Course not found.'

                ],404);

            }



            $totalScore = 0;


            $details = [];



            foreach(
                $course->assignments
                as $assignment
            ){



                $submission =
                AssignmentSubmission::where(

                    'assignment_id',

                    $assignment->id

                )
                ->where(

                    'student_id',

                    $studentId

                )
                ->first();



                if(
                    !$submission ||
                    $submission->score === null
                ){

                    continue;

                }



                $category =
                $assignment->gradeCategory;



                if(!$category){

                    continue;

                }



                $score =
                $submission->score;



                $weight =
                $category->weight;



                $calculated =
                ($score * $weight) / 100;



                $totalScore += $calculated;



                $details[]=[


                    'assignment'=>
                    $assignment->title,


                    'category'=>
                    $category->name,


                    'score'=>
                    $score,


                    'weight'=>
                    $weight,


                    'result'=>
                    round(
                        $calculated,
                        2
                    )

                ];

            }



            return response()->json([


                'success'=>true,


                'data'=>[


                    'student'=>
                    $student->first_name_english
                    .' '.
                    $student->last_name_english,


                    'course'=>
                    $course->name,


                    'details'=>
                    $details,


                    'final_score'=>
                    round(
                        $totalScore,
                        2
                    )

                ]

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }


}