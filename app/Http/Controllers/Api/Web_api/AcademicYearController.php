<?php

namespace App\Http\Controllers\Api\Web_api;


use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Dropdown Academic Year
     */
    public function dropdown()
    {

        try{


            $academicYears = AcademicYear::select(

                    'id',

                    'academic_year'

                )
                ->orderByDesc('id')
                ->get();




            return response()->json([


                'success'=>true,


                'message'=>'Academic years loaded successfully.',


                'data'=>$academicYears



            ],200);



        }catch(\Exception $e){


            return response()->json([


                'success'=>false,


                'message'=>'Failed to load academic years.',


                'error'=>$e->getMessage()



            ],500);


        }


    }



}