<?php

namespace App\Http\Controllers\Api\Web_api;


use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;


class SemesterController extends Controller
{


    /**
     * Dropdown Semester
     */
    public function dropdown()
    {

        try {


            $semesters = Semester::select(

                    'id',

                    'semester_name',
                    'academic_year_id'

                )
                ->orderBy('id')
                ->get();



            return response()->json([


                'success'=>true,


                'message'=>'Semesters loaded successfully.',


                'data'=>$semesters



            ],200);



        }catch(\Exception $e){


            return response()->json([


                'success'=>false,


                'message'=>'Failed to load semesters.',


                'error'=>$e->getMessage()



            ],500);


        }


    }



}