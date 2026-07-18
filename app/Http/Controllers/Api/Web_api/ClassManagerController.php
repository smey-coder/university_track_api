<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClassManager;
use App\Models\Student;
use App\Models\StudentClass;


class ClassManagerController extends Controller
{


    /**
     * ==========================================
     * Display Class Manager List
     * ==========================================
     */
    public function index()
    {
        try {


            $classManagers = ClassManager::with([
                'student.department',

                'studentClass.department',

                'studentClass.classSemesters.semester',

                'studentClass.classSemesters.academicYear'

            ])

            ->latest()

            ->paginate(10);



            return response()->json([

                'success'=>true,

                'message'=>'Class manager list loaded successfully.',

                'data'=>$classManagers

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to load class manager list.',

                'error'=>$e->getMessage()

            ],500);

        }
    }




    /**
     * ==========================================
     * Assign Student To Class
     * ==========================================
     */
    public function store(Request $request)
    {
        try {


            $request->validate([


                'student_id'=>
                'required|exists:students,id',


                'class_id'=>
                'required|exists:student_classes,id',


                'assigned_date'=>
                'required|date',


            ]);




            $exists = ClassManager::where(
                    'student_id',
                    $request->student_id
                )
                ->where(
                    'status',
                    'Active'
                )
                ->exists();



            if($exists){


                return response()->json([

                    'success'=>false,

                    'message'=>'Student already has an active class.'

                ],422);

            }




            DB::transaction(function() use($request){


                ClassManager::create([


                    'student_id'=>
                    $request->student_id,


                    'class_id'=>
                    $request->class_id,


                    'assigned_date'=>
                    $request->assigned_date,


                    'status'=>
                    'Active'


                ]);




                Student::where(
                    'id',
                    $request->student_id
                )
                ->update([

                    'class_id'=>
                    $request->class_id

                ]);

            });




            return response()->json([

                'success'=>true,

                'message'=>'Student assigned successfully.'

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to assign student.',

                'error'=>$e->getMessage()

            ],500);

        }
    }




    /**
     * ==========================================
     * Show Class Manager
     * ==========================================
     */
    public function show($id)
    {
        try {


            $classManager = ClassManager::with([

                'student.department',

                'studentClass.department',

                'studentClass.academicYear',

                'studentClass.classSemesters.semester',

                'studentClass.classSemesters.academicYear'

            ])
            ->find($id);



            if(!$classManager){


                return response()->json([

                    'success'=>false,

                    'message'=>'Class manager not found.'

                ],404);

            }




            return response()->json([

                'success'=>true,

                'message'=>'Class manager retrieved successfully.',

                'data'=>$classManager

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to retrieve class manager.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
        /**
     * ==========================================
     * Update Class Assignment
     * ==========================================
     */
    public function update(Request $request,$id)
    {
        try {


            $classManager = ClassManager::find($id);


            if(!$classManager){

                return response()->json([

                    'success'=>false,

                    'message'=>'Class manager not found.'

                ],404);

            }



            $request->validate([


                'class_id'=>
                'required|exists:student_classes,id',


                'assigned_date'=>
                'required|date',


                'status'=>
                'required|string'


            ]);




            DB::transaction(function() use($request,$classManager){



                if($classManager->class_id != $request->class_id){



                    // close old class

                    $classManager->update([

                        'status'=>'Completed'

                    ]);




                    // create new history

                    ClassManager::create([


                        'student_id'=>
                        $classManager->student_id,


                        'class_id'=>
                        $request->class_id,


                        'assigned_date'=>
                        $request->assigned_date,


                        'status'=>
                        'Active'


                    ]);



                }else{


                    $classManager->update([


                        'assigned_date'=>
                        $request->assigned_date,


                        'status'=>
                        $request->status


                    ]);

                }




                Student::where(
                    'id',
                    $classManager->student_id
                )
                ->update([

                    'class_id'=>
                    $request->class_id

                ]);



            });




            return response()->json([


                'success'=>true,


                'message'=>
                'Student class updated successfully.'


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
     * Delete Class Assignment
     * ==========================================
     */
    public function destroy($id)
    {
        try {


            $classManager = ClassManager::find($id);



            if(!$classManager){


                return response()->json([

                    'success'=>false,

                    'message'=>'Class manager not found.'

                ],404);

            }




            $classManager->delete();




            return response()->json([

                'success'=>true,

                'message'=>'Class assignment deleted successfully.'

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to delete class assignment.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
    /**
     * ==========================================
     * Students By Class
     * ==========================================
     */
    public function studentsByClass($classId)
    {
        try {


            $class = StudentClass::with([


                'department',

                'academicYear',

                'classSemesters.semester',

                'classSemesters.academicYear'


            ])
            ->find($classId);



            if(!$class){


                return response()->json([

                    'success'=>false,

                    'message'=>'Class not found.'

                ],404);

            }




            $students = Student::with([

                'department',

                'studentClass'

            ])

            ->where(
                'class_id',
                $classId
            )

            ->orderBy('student_code')

            ->get();





            return response()->json([


                'success'=>true,


                'message'=>'Students loaded successfully.',


                'class'=>$class,


                'total_students'=>
                $students->count(),


                'students'=>$students



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
     * Student Class History
     * ==========================================
     */
    public function studentHistory($studentId)
    {
        try {


            $student = Student::find($studentId);



            if(!$student){


                return response()->json([

                    'success'=>false,

                    'message'=>'Student not found.'

                ],404);

            }





            $history = ClassManager::with([


                'studentClass.department',

                'studentClass.academicYear',

                'studentClass.classSemesters.semester',

                'studentClass.classSemesters.academicYear'


            ])

            ->where(
                'student_id',
                $studentId
            )

            ->orderByDesc('assigned_date')

            ->get();




            return response()->json([


                'success'=>true,


                'student'=>$student,


                'history'=>$history



            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Failed to load history.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
    /**
     * ==========================================
     * Current Student Class
     * ==========================================
     */
    public function myClass(Request $request)
    {
        try {


            $student = Student::with([


                'department',


                'studentClass.academicYear',


                'studentClass.classSemesters.semester',


                'studentClass.classSemesters.academicYear'


            ])

            ->where(
                'user_id',
                auth()->id()
            )

            ->first();



            if(!$student){


                return response()->json([

                    'success'=>false,

                    'message'=>'Student profile not found.'

                ],404);

            }





            return response()->json([


                'success'=>true,


                'data'=>[

                    'student'=>$student,

                    'class'=>$student->studentClass

                ]


            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Cannot load student class.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
    /**
     * ==========================================
     * Students Without Class
     * ==========================================
     */
    public function availableStudents()
    {
        try {


            $students = Student::with([
                'department'
            ])

            ->whereNull('class_id')

            ->orderBy('student_code')

            ->get();




            return response()->json([

                'success'=>true,

                'data'=>$students

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Cannot load available students.',

                'error'=>$e->getMessage()

            ],500);

        }
    }
}