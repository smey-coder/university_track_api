<?php
namespace App\Http\Controllers\Api\Web_api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\GradeCategory;
use App\Models\StudentClass;
use Carbon\Carbon;
class AssignmentController extends Controller
{
    /**
     * Display assignments
     */
    public function index(Request $request)
    {
        try {

            $user = auth()->user();


            $query = Assignment::with([
                'course',
                'teacher',
                'class',
                'gradeCategory',

                'submissions',

                'groups'
            ]);


            // ==========================
            // SEARCH
            // ==========================

            if($request->search){

                $search = $request->search;

                $query->where(function($q) use($search){

                    $q->where(
                        'title',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'assignment_code',
                        'like',
                        "%{$search}%"
                    );

                });

            }



            // ==========================
            // TEACHER FILTER
            // ==========================

            if($request->teacher_id){

                $query->where(
                    'teacher_id',
                    $request->teacher_id
                );

            }



            // ==========================
            // COURSE FILTER
            // ==========================

            if($request->course_id){

                $query->where(
                    'course_id',
                    $request->course_id
                );

            }




            // ==========================
            // CLASS FILTER
            // ==========================

            if($request->class_id){

                $query->where(
                    'class_id',
                    $request->class_id
                );

            }





            // ==========================
            // ASSIGNMENT TYPE
            // Homework
            // Assignment
            // Quiz
            // Project
            // ==========================

            if($request->assignment_type){

                $query->where(
                    'assignment_type',
                    $request->assignment_type
                );

            }




            // ==========================
            // SUBMISSION TYPE
            // Individual / Group
            // ==========================

            if($request->submission_type){

                $query->where(
                    'submission_type',
                    $request->submission_type
                );

            }




            // ==========================
            // STATUS
            // Draft
            // Open
            // Closed
            // ==========================

            if($request->status){

                $query->where(
                    'status',
                    $request->status
                );

            }




            // ==========================
            // DATE FILTER
            // ==========================

            if($request->due_from){

                $query->whereDate(
                    'created_at',
                    '>=',
                    $request->due_from
                );

            }



            if($request->due_to){

                $query->whereDate(
                    'due_date',
                    '<=',
                    $request->due_to
                );

            }





            // ==========================
            // ROLE FILTER
            // ==========================


            if($user->hasRole('Teacher')){


                $teacher = Teacher::where(
                    'user_id',
                    $user->id
                )->first();



                if($teacher){


                    $query->where(
                        'teacher_id',
                        $teacher->id
                    );


                }

            }





            // ==========================
            // PAGINATION
            // ==========================


            $assignments = $query
                ->latest()
                ->paginate(10);



            return response()->json([


                'success'=>true,


                'data'=>$assignments->items(),


                'pagination'=>[

                    'current_page'=>$assignments->currentPage(),

                    'last_page'=>$assignments->lastPage(),

                    'total'=>$assignments->total()

                ]


            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }
    }
    /**
     * Dropdown data
     */
    public function getFormDataDependencies()
    {
        try {
            $user = auth()->user();
            // ==========================
            // ADMIN
            // ==========================

            if ($user->hasRole('Admin')) {


                $courses = Course::where('status','Active')
                    ->select(
                        'id',
                        'course_code',
                        'course_name',
                        'teacher_id'
                    )
                    ->with('teacher:id,first_name_english,last_name_english')
                    ->orderBy('course_name')
                    ->get();



                $teachers = Teacher::where('status','Active')
                    ->select(
                        'id',
                        'first_name_english',
                        'last_name_english'
                    )
                    ->orderBy('first_name_english')
                    ->get();



                $classes = StudentClass::with([
                        'department',
                        'academicYear'
                    ])
                    ->where('status','1')
                    ->select(
                        'id',
                        'class_name',
                        'department_id',
                        'academic_year_id'
                    )
                    ->orderBy('class_name')
                    ->get();



                return response()->json([

                    'success'=>true,

                    'teachers'=>$teachers,

                    'courses'=>$courses,

                    'classes'=>$classes

                ]);

            }



            // ==========================
            // TEACHER
            // ==========================


            $teacher = Teacher::where(
                'user_id',
                $user->id
            )->first();



            if(!$teacher){

                return response()->json([

                    'success'=>false,

                    'message'=>'Teacher not found.'

                ],404);

            }





            // Teacher courses

            $courses = Course::where(
                    'teacher_id',
                    $teacher->id
                )
                ->where(
                    'status',
                    'Active'
                )
                ->select(
                    'id',
                    'course_code',
                    'course_name'
                )
                ->orderBy('course_name')
                ->get();







            // Teacher classes

            $classes = StudentClass::whereHas(
                    'schedules',
                    function($q) use($teacher){

                        $q->where(
                            'teacher_id',
                            $teacher->id
                        );

                    }
                )
                ->where(
                    'status',
                    'Active'
                )
                ->select(
                    'id',
                    'class_name'
                )
                ->orderBy('class_name')
                ->get();






            return response()->json([


                'success'=>true,


                'teachers'=>[

                    [

                        'id'=>$teacher->id,

                        'first_name_english'=>
                        $teacher->first_name_english,

                        'last_name_english'=>
                        $teacher->last_name_english

                    ]

                ],


                'courses'=>$courses,


                'classes'=>$classes


            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }
    /**
     * Store Assignment
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            $request->validate([
                'course_id'    => 'required|exists:courses,id',
                'title'        => 'required|string|max:255',
                'description'  => 'nullable|string',
                'due_date'     => 'required|date',
                'total_score'  => 'required|numeric|min:0',
                'status'       => 'required|in:Open,Closed',
                //Admin only
                'teacher_id'   => 'nullable|exists:teachers,id',
                'assignment_type' => 'required|in:Homework,Assignment,Quiz,Project,Orther',
                'submission_type' =>'required|in:Individual,Group',
            ]);

            /**
             * ===================================
             * Determine Teacher
             * ===================================
             */

            if ($user->hasRole('Admin')) {

                if (!$request->teacher_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Teacher is required.'
                    ],422);
                }

                $teacher = Teacher::findOrFail($request->teacher_id);

            } else {

                $teacher = Teacher::where('user_id',$user->id)->first();

                if(!$teacher){
                    return response()->json([
                        'success'=>false,
                        'message'=>'Teacher not found.'
                    ],404);
                }

            }

            /**
             * ===================================
             * Verify Course belongs to Teacher
             * ===================================
             */

            $course = Course::where('id',$request->course_id)
                            ->where('teacher_id',$teacher->id)
                            ->first();

            if(!$course){

                return response()->json([
                    'success'=>false,
                    'message'=>'Selected course is not assigned to this teacher.'
                ],403);

            }

            /**
             * ===================================
             * Generate Assignment Code
             * ASS-WEB101-DARA-2026-0715-0001
             * ===================================
             */

            $teacherName = strtoupper(
                preg_replace('/[^A-Za-z]/','',$teacher->first_name_english)
            );

            $courseCode = strtoupper($course->course_code);

            $year = Carbon::now()->format('Y');

            $day = Carbon::now()->format('md');

            $sequence = Assignment::where('teacher_id',$teacher->id)
                            ->whereDate('created_at',Carbon::today())
                            ->count() + 1;

            $assignmentCode =
                "ASS-{$courseCode}-{$teacherName}-{$year}-{$day}-"
                . str_pad($sequence,4,'0',STR_PAD_LEFT);

            /**
             * ===================================
             * Save Assignment
             * ===================================
             */

            $assignment = Assignment::create([

                'assignment_code' => $assignmentCode,
                'course_id'       => $course->id,
                'teacher_id'      => $teacher->id,
                'assignment_type' => $request->assignment_type,
                'submission_type' => $request->submission_type,
                'title'           => $request->title,
                'description'     => $request->description,
                'due_date'        => $request->due_date,
                'total_score'     => $request->total_score,
                'status'          => $request->status,

            ]);

            return response()->json([

                'success'=>true,

                'message'=>'Assignment created successfully.',

                'data'=>$assignment->load([
                    'course',
                    'teacher'
                ])

            ],201);

        } catch(\Exception $e){

            return response()->json([

                'success'=>false,

                'message'=>'Failed to create assignment.',

                'error'=>$e->getMessage()

            ],500);

        }
    }

    /**
     * Show Assignment
     */
    public function show($id)
    {

        try {
            $assignment = Assignment::with([
                'course',
                'teacher',
                'class',

                'gradeCategory',

                'submissions.student',

                'groups.members.student'

            ])->find($id);

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found.'
                ], 404);
            }

            $user = auth()->user();

            /**
             * ===============================
             * ADMIN
             * ===============================
             */
            if ($user->hasRole('Admin')) {

                return response()->json([
                    'success' => true,
                    'data' => $assignment
                ]);
            }

            /**
             * ===============================
             * TEACHER
             * ===============================
             */
            if ($user->hasRole('Teacher')) {

                $teacher = Teacher::where('user_id', $user->id)->first();

                if (!$teacher) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Teacher not found.'
                    ], 404);
                }

                if ($assignment->teacher_id != $teacher->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to view this assignment.'
                    ], 403);
                }

                return response()->json([
                    'success' => true,
                    'data' => $assignment
                ]);
            }

            /**
             * ===============================
             * OTHER ROLES
             * ===============================
             */
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve assignment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Assignment
     */
    public function update(Request $request,$id)
    {
       try {
            $assignment = Assignment::find($id);
            if(!$assignment){

                return response()->json([

                    'success'=>false,

                    'message'=>'Assignment not found'
                ],404);
            }
            $user = auth()->user();
            $request->validate([
                'course_id'
                =>'required|exists:courses,id',
                'teacher_id'
                =>'nullable|exists:teachers,id',
                'title'
                =>'required|string|max:255',
                'description'
                =>'nullable|string',
                'due_date'
                =>'required|date',
                'total_score'
                =>'required|numeric|min:0',
                'status'
                =>'required|in:Open,Closed',

                'assignment_type' => 'required|in:Homework,Assignment,Quiz,Project,Orther',
                'submission_type' =>'required|in:Individual,Group',
            ]);
            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */

            if($user->hasRole('Admin')){


                $teacher = Teacher::find(
                    $request->teacher_id
                );


                if(!$teacher){

                    return response()->json([

                        'success'=>false,

                        'message'=>'Teacher not found'

                    ],404);

                }
            }
            /*
            |--------------------------------------------------------------------------
            | TEACHER
            |--------------------------------------------------------------------------
            */

            else{
                $teacher = Teacher::where(
                    'user_id',
                    $user->id
                )
                ->first();
                if(!$teacher){
                    return response()->json([

                        'success'=>false,

                        'message'=>'Teacher profile not found'

                    ],404);
                }
                if(
                    $assignment->teacher_id
                    !=
                    $teacher->id
                ){
                    return response()->json([

                        'success'=>false,

                        'message'=>'You cannot edit this assignment'

                    ],403);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK COURSE OWNER
            |--------------------------------------------------------------------------
            */
            $course = Course::where('id',$request->course_id)
                ->where('teacher_id',$teacher->id)
                ->first();
            if(!$course){
                return response()->json([

                    'success'=>false,

                    'message'=>
                    'This course does not belong to selected teacher.'

                ],403);


            }





            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */


            $assignment->update([


                'course_id'
                =>$course->id,


                'teacher_id'
                =>$teacher->id,

                'assignment_type' => $request->assignment_type,
                'submission_type' => $request->submission_type,

                'title'
                =>$request->title,


                'description'
                =>$request->description,


                'due_date'
                =>$request->due_date,


                'total_score'
                =>$request->total_score,


                'status'
                =>$request->status,


            ]);
            return response()->json([


                'success'=>true,
                'message'=>
                'Assignment updated successfully',
                'data'=>
                $assignment->fresh()
                ->load([
                    'course',
                    'teacher'
                ])
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>
                'Update assignment failed',


                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Delete Assignment
     */
    public function destroy($id)
    {

        try {
            $assignment = Assignment::find($id);

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found.'
                ], 404);
            }

            $user = auth()->user();

            /**
             * =====================================
             * ADMIN
             * Can delete all assignments
             * =====================================
             */
            if ($user->hasRole('Admin')) {

                $assignment->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Assignment deleted successfully.'
                ]);
            }

            /**
             * =====================================
             * TEACHER
             * Can delete only own assignments
             * =====================================
             */
            if ($user->hasRole('Teacher')) {

                $teacher = Teacher::where('user_id', $user->id)->first();

                if (!$teacher) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Teacher not found.'
                    ], 404);
                }

                if ($assignment->teacher_id != $teacher->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to delete this assignment.'
                    ], 403);
                }

                $assignment->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Assignment deleted successfully.'
                ]);
            }

            /**
             * =====================================
             * OTHER ROLES
             * =====================================
             */
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete assignment.',
                'error' => $e->getMessage()
            ], 500);
        }

    }
    /**
     * ==========================================
     * Assign Grade Category To Assignment
     * ==========================================
     */
    public function assignGradeCategory(Request $request,$id)
    {

        try {


            $assignment = Assignment::find($id);


            if(!$assignment){

                return response()->json([

                    'success'=>false,

                    'message'=>'Assignment not found.'

                ],404);

            }


            $request->validate([

                'grade_category_id'=>
                'required|exists:grade_categories,id'

            ]);



            $category = GradeCategory::find(
                $request->grade_category_id
            );



            // Check same course

            if(
                $category->course_id
                !=
                $assignment->course_id
            ){

                return response()->json([

                    'success'=>false,

                    'message'=>
                    'Grade category must belong to the same course.'

                ],422);

            }



            $assignment->update([

                'grade_category_id'=>
                $request->grade_category_id

            ]);



            return response()->json([

                'success'=>true,

                'message'=>
                'Grade category assigned successfully.',


                'data'=>
                $assignment->load([
                    'course',
                    'gradeCategory'
                ])

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }

}