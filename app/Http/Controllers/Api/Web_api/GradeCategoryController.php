<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GradeCategory;
use App\Models\Course;
use Illuminate\Support\Facades\DB;


class GradeCategoryController extends Controller
{


    /**
     * =====================================
     * Display Categories By Course
     * =====================================
     */
    public function index($courseId)
    {

        try {

            $categories = GradeCategory::where(
                'course_id',
                $courseId
            )
            ->with('course')
            ->get();


            return response()->json([

                'success'=>true,

                'data'=>$categories

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
     * Create Category
     * =====================================
     */
    public function store(Request $request)
    {

        DB::beginTransaction();


        try {


            $request->validate([


                'course_id'=>
                'required|exists:courses,id',


                'name'=>
                'required|string|max:100',


                'weight'=>
                'required|numeric|min:0|max:100'


            ]);



            // Check total weight

            $totalWeight =
            GradeCategory::where(
                'course_id',
                $request->course_id
            )
            ->sum('weight');



            if(
                ($totalWeight + $request->weight)
                > 100
            ){

                return response()->json([

                    'success'=>false,

                    'message'=>
                    'Total grade weight cannot exceed 100%.'

                ],422);

            }



            $category =
            GradeCategory::create([

                'course_id'=>
                $request->course_id,


                'name'=>
                $request->name,


                'weight'=>
                $request->weight

            ]);



            DB::commit();



            return response()->json([

                'success'=>true,

                'message'=>
                'Grade category created successfully.',


                'data'=>$category

            ],201);



        }catch(\Exception $e){


            DB::rollBack();


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }




    /**
     * =====================================
     * Update Category
     * =====================================
     */
    public function update(Request $request,$id)
    {

        try {


            $category =
            GradeCategory::find($id);



            if(!$category){


                return response()->json([

                    'success'=>false,

                    'message'=>
                    'Grade category not found.'

                ],404);

            }



            $request->validate([


                'name'=>
                'required|string|max:100',


                'weight'=>
                'required|numeric|min:0|max:100'


            ]);



            $otherWeight =
            GradeCategory::where(
                'course_id',
                $category->course_id
            )
            ->where(
                'id',
                '!=',
                $id
            )
            ->sum('weight');



            if(
                ($otherWeight+$request->weight)
                >100
            ){


                return response()->json([

                    'success'=>false,

                    'message'=>
                    'Total weight cannot exceed 100%.'

                ],422);

            }



            $category->update([


                'name'=>
                $request->name,


                'weight'=>
                $request->weight


            ]);



            return response()->json([

                'success'=>true,

                'message'=>
                'Category updated successfully.',

                'data'=>$category

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
     * Delete Category
     * =====================================
     */
    public function destroy($id)
    {

        try {


            $category =
            GradeCategory::find($id);



            if(!$category){


                return response()->json([

                    'success'=>false,

                    'message'=>
                    'Category not found.'

                ],404);

            }



            $category->delete();



            return response()->json([

                'success'=>true,

                'message'=>
                'Category deleted successfully.'

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }


}