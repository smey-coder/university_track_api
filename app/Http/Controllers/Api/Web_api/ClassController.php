<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentClass;
use App\Models\Student;
use App\Models\ClassSemester;

class ClassController extends Controller
{

        /**
         * ==========================================
         * Display a listing of classes
         * ==========================================
         */
        public function index(Request $request)
        {
            try {

                $query = StudentClass::with([
                    'department',
                    'academicYear',
                    'classSemesters.semester',
                    'classSemesters.academicYear'
                ])
                ->withCount('students');


                /*
                |--------------------------------------------------------------------------
                | Search
                |--------------------------------------------------------------------------
                */
                if ($request->filled('search')) {

                    $search = $request->search;


                    $query->where(function($q) use($search){

                        $q->where(
                            'class_name',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'room',
                            'like',
                            "%{$search}%"
                        )


                        ->orWhereHas('academicYear',function($year) use($search){

                            $year->where(
                                'academic_year',
                                'like',
                                "%{$search}%"
                            );

                        });

                    });

                }



                /*
                |--------------------------------------------------------------------------
                | Department Filter
                |--------------------------------------------------------------------------
                */
                if($request->filled('department_id')){

                    $query->where(
                        'department_id',
                        $request->department_id
                    );

                }



                /*
                |--------------------------------------------------------------------------
                | Academic Year Filter
                |--------------------------------------------------------------------------
                */
                if($request->filled('academic_year_id')){


                    $query->whereHas(
                        'classSemesters',
                        function($q) use($request){

                            $q->where(
                                'academic_year_id',
                                $request->academic_year_id
                            );

                        }
                    );

                }



                /*
                |--------------------------------------------------------------------------
                | Semester Filter
                |--------------------------------------------------------------------------
                */
                if($request->filled('semester_id')){


                    $query->whereHas(
                        'classSemesters',
                        function($q) use($request){

                            $q->where(
                                'semester_id',
                                $request->semester_id
                            );

                        }
                    );

                }



                /*
                |--------------------------------------------------------------------------
                | Status Filter
                |--------------------------------------------------------------------------
                */
                if($request->filled('status')){

                    $query->where(
                        'status',
                        $request->status
                    );

                }



                $classes = $query
                    ->latest()
                    ->paginate(
                        $request->get('per_page',10)
                    );



                return response()->json([

                    'success'=>true,

                    'message'=>'Class list retrieved successfully.',

                    'data'=>$classes

                ]);



            }catch(\Exception $e){


                return response()->json([

                    'success'=>false,

                    'message'=>'Failed to load class list.',

                    'error'=>$e->getMessage()

                ],500);

            }
        }




        /**
         * ==========================================
         * Store new class
         * ==========================================
         */
        public function store(Request $request)
        {
            try {


                $request->validate([


                    'academic_year_id'=>
                    'required|exists:academic_years,id',


                    'semester_ids'=>
                    'required|array|min:1',


                    'semester_ids.*'=>
                    'exists:semesters,id',


                    'department_id'=>
                    'required|exists:departments,id',


                    'class_name'=>
                    'required|string|max:100|unique:student_classes,class_name',


                    'room'=>
                    'nullable|string|max:50',


                    'max_students'=>
                    'required|integer|min:1',


                    'status'=>
                    'required|in:1,0',


                ]);




                // Create Class

                $class = StudentClass::create([


                    'academic_year_id'=>
                    $request->academic_year_id,


                    'department_id'=>
                    $request->department_id,


                    'class_name'=>
                    $request->class_name,


                    'room'=>
                    $request->room,


                    'max_students'=>
                    $request->max_students,


                    'status'=>
                    $request->status,


                ]);





                // Assign semesters

                foreach($request->semester_ids as $semester_id){


                    ClassSemester::create([


                        'class_id'=>
                        $class->id,


                        'academic_year_id'=>
                        $request->academic_year_id,


                        'semester_id'=>
                        $semester_id,


                    ]);

                }





                return response()->json([


                    'success'=>true,


                    'message'=>'Class created successfully.',


                    'data'=>$class->load([

                        'department',

                        'academicYear',

                        'classSemesters.semester',

                        'classSemesters.academicYear'

                    ])


                ],201);



            }catch(\Exception $e){


                return response()->json([


                    'success'=>false,


                    'message'=>'Failed to create class.',


                    'error'=>$e->getMessage()


                ],500);


            }
        }






        /**
         * ==========================================
         * Update class
         * ==========================================
         */
        public function update(Request $request,$id)
        {
            try {


                $class = StudentClass::find($id);



                if(!$class){


                    return response()->json([

                        'success'=>false,

                        'message'=>'Class not found.'

                    ],404);

                }





                $request->validate([


                    'academic_year_id'=>
                    'required|exists:academic_years,id',


                    'semester_ids'=>
                    'required|array|min:1',


                    'semester_ids.*'=>
                    'exists:semesters,id',


                    'department_id'=>
                    'required|exists:departments,id',


                    'class_name'=>
                    'required|string|max:100|unique:student_classes,class_name,'.$class->id,


                    'room'=>
                    'nullable|string|max:50',


                    'max_students'=>
                    'required|integer|min:1',


                    'status'=>
                    'required|in:1,0',


                ]);





                // Update Class

                $class->update([


                    'academic_year_id'=>
                    $request->academic_year_id,


                    'department_id'=>
                    $request->department_id,


                    'class_name'=>
                    $request->class_name,


                    'room'=>
                    $request->room,


                    'max_students'=>
                    $request->max_students,


                    'status'=>
                    $request->status,


                ]);





                /*
                |--------------------------------------------------------------------------
                | Replace semesters
                |--------------------------------------------------------------------------
                */

                ClassSemester::where(
                    'class_id',
                    $class->id
                )->delete();




                foreach($request->semester_ids as $semester_id){


                    ClassSemester::create([


                        'class_id'=>
                        $class->id,


                        'academic_year_id'=>
                        $request->academic_year_id,


                        'semester_id'=>
                        $semester_id,


                    ]);

                }





                return response()->json([


                    'success'=>true,


                    'message'=>'Class updated successfully.',


                    'data'=>$class->load([


                        'department',

                        'academicYear',

                        'classSemesters.semester',

                        'classSemesters.academicYear'


                    ])


                ]);



            }catch(\Exception $e){


                return response()->json([


                    'success'=>false,


                    'message'=>'Failed to update class.',


                    'error'=>$e->getMessage()


                ],500);


            }
        }
        /**
     * ==========================================
     * Show class details
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
                'students.department'
            ])
            ->withCount('students')
            ->find($id);


            if(!$class){

                return response()->json([

                    'success'=>false,

                    'message'=>'Class not found.'

                ],404);

            }


            return response()->json([

                'success'=>true,

                'message'=>'Class details retrieved successfully.',

                'data'=>$class

            ]);


        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to load class details.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
    /**
     * ==========================================
     * Delete Class
     * ==========================================
     */
    public function destroy($id)
    {
        try {


            $class = StudentClass::find($id);



            if(!$class){


                return response()->json([

                    'success'=>false,

                    'message'=>'Class not found.'

                ],404);

            }



            // Check students

            if($class->students()->count() > 0){


                return response()->json([

                    'success'=>false,

                    'message'=>'Cannot delete class because students exist.'

                ],400);

            }




            // Check schedules

            if($class->schedules()->count() > 0){


                return response()->json([

                    'success'=>false,

                    'message'=>'Cannot delete class because schedules exist.'

                ],400);

            }




            // Delete class semesters first

            ClassSemester::where(
                'class_id',
                $class->id
            )->delete();



            $class->delete();



            return response()->json([

                'success'=>true,

                'message'=>'Class deleted successfully.'

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to delete class.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
    /**
     * ==========================================
     * Class Dropdown
     * ==========================================
     */
    public function dropdown()
    {
        try {


            $classes = StudentClass::where(
                    'status',
                    1
                )
                ->orderBy('class_name')
                ->get([
                    'id',
                    'class_name'
                ]);



            return response()->json([

                'success'=>true,

                'data'=>$classes

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to load dropdown.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
    /**
     * ==========================================
     * Available Classes
     * ==========================================
     */
    public function availableClasses()
    {
        try {


            $classes = StudentClass::with([

                    'department',

                    'academicYear',

                    'classSemesters.semester',

                    'classSemesters.academicYear'

                ])

                ->withCount('students')

                ->where('status',1)

                ->orderBy('class_name')

                ->get();



            return response()->json([

                'success'=>true,

                'message'=>'Available classes loaded successfully.',

                'data'=>$classes

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to load available classes.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
        /**
     * ==========================================
     * Students in Class
     * ==========================================
     */
    public function students($id)
    {
        try {


            $class = StudentClass::with([

                'department',

                'academicYear',

                'classSemesters.semester',

                'classSemesters.academicYear',

                'students.department'

            ])->find($id);



            if(!$class){


                return response()->json([

                    'success'=>false,

                    'message'=>'Class not found.'

                ],404);

            }



            return response()->json([


                'success'=>true,


                'class'=>[


                    'id'=>$class->id,


                    'class_name'=>$class->class_name,


                    'room'=>$class->room,


                    'department'=>
                    $class->department?->department_name_english,


                    'academic_year'=>
                    $class->academicYear?->academic_year,



                    'semesters'=>
                    $class->classSemesters
                    ->map(function($item){


                        return [

                            'semester_id'=>
                            $item->semester_id,


                            'semester_name'=>
                            $item->semester?->semester_name,


                            'academic_year'=>
                            $item->academicYear?->academic_year

                        ];


                    })


                ],



                'total_students'=>
                $class->students->count(),



                'students'=>
                $class->students



            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to load students.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
    /**
     * ==========================================
     * Class Statistics
     * ==========================================
     */
    public function statistics($id)
    {
        try {


            $class = StudentClass::withCount('students')
                ->find($id);



            if(!$class){


                return response()->json([

                    'success'=>false,

                    'message'=>'Class not found.'

                ],404);

            }



            $remaining =
                $class->max_students -
                $class->students_count;



            $percentage = 0;


            if($class->max_students > 0){

                $percentage = round(

                    ($class->students_count /
                    $class->max_students) * 100,

                    2

                );

            }



            return response()->json([


                'success'=>true,


                'data'=>[


                    'class_name'=>
                    $class->class_name,


                    'room'=>
                    $class->room,


                    'max_students'=>
                    $class->max_students,


                    'current_students'=>
                    $class->students_count,


                    'remaining_seats'=>
                    $remaining,


                    'percentage'=>
                    $percentage


                ]

            ]);



        }catch(\Exception $e){


            return response()->json([


                'success'=>false,


                'message'=>'Cannot load statistics.',


                'error'=>$e->getMessage()


            ],500);

        }
    }
    /**
     * ==========================================
     * Promote Students
     * ==========================================
     */
    public function promoteStudents(Request $request)
    {
        try {


            $request->validate([


                'from_class_id'=>
                'required|exists:student_classes,id',


                'to_class_id'=>
                'required|exists:student_classes,id'


            ]);



            Student::where(
                'class_id',
                $request->from_class_id
            )
            ->update([

                'class_id'=>
                $request->to_class_id

            ]);



            return response()->json([


                'success'=>true,


                'message'=>
                'Students promoted successfully.'


            ]);



        }catch(\Exception $e){


            return response()->json([


                'success'=>false,


                'message'=>'Promotion failed.',


                'error'=>$e->getMessage()


            ],500);

        }
    }
}