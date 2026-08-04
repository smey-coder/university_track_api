<?php
namespace App\Http\Controllers\Api\Web_api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\GradeCategory;
use App\Models\StudentClass;
use App\Models\SubjectSchedule;
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
            // STUDENT BLOCK
            // ==========================

            if($user->hasRole('Student')){


                return response()->json([

                    'success'=>false,

                    'message'=>'Students cannot access assignment form data'

                ],403);


            }
            // ==========================
            // ADMIN
            // ==========================

            if ($user->hasRole('Admin')) {
                $schedules = SubjectSchedule::with([
                    'course:id,course_code,course_name',
                    'teacher:id,first_name_english,last_name_english',
                    'studentClass:id,class_name'
                ])
                    ->where('status', 'active')
                    ->get()
                    ->map(function ($schedule) {
                        return [
                           'id'=>$schedule->id, // schedule id
                            'course_id'=>$schedule->course_id,
                            'class_id'=>$schedule->class_id,
                            'course_code'=>$schedule->course->course_code,
                            'course_name'=>$schedule->course->course_name,
                            'teacher_name' => $schedule->teacher->first_name_english . ' ' . $schedule->teacher->last_name_english,
                            'class_name'=>$schedule->studentClass->class_name,
                        ];
                    });

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
                    'courses'=>$schedules,
                    'teachers'=>$teachers,
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
            // $courses = Course::where('teacher_id',$teacher->id)
            //     ->where('status','Active')
            //     ->select(
            //         'id',
            //         'course_code',
            //         'course_name'
            //     )
            //     ->orderBy('course_name')
            //     ->get();

            $schedules = SubjectSchedule::with([
                'course:id,course_code,course_name'
            ])
            ->where('teacher_id', $teacher->id)
            ->where('status', 'Active')
            ->orderBy('course_id')
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
                    '1'
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
                'courses'=>$schedules,
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


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'schedule_id'
                => 'required|exists:subject_schedules,id',

            'title'
                => 'required|string|max:255',

            'description'
                => 'nullable|string',

            'due_date'
                => 'required|date',

            'total_score'
                => 'required|numeric|min:0',

            'status'
                => 'required|in:Open,Closed',

            'assignment_type'
                => 'required|in:Homework,Assignment,Quiz,Project,Orther',

            'submission_type'
                => 'required|in:Individual,Group',

        ]);



        /*
        |--------------------------------------------------------------------------
        | LOAD SUBJECT SCHEDULE
        |--------------------------------------------------------------------------
        */

        $schedule = SubjectSchedule::with([
            'course',
            'teacher',
            'studentClass'
        ])
        ->findOrFail($request->schedule_id);



        /*
        |--------------------------------------------------------------------------
        | DETERMINE TEACHER
        |--------------------------------------------------------------------------
        */

        if($user->hasRole('Admin')){


            $teacher = $schedule->teacher;


            if(!$teacher){

                return response()->json([

                    'success'=>false,

                    'message'=>'Teacher not found.'

                ],404);

            }


        }else{


            $teacher = Teacher::where(
                'user_id',
                $user->id
            )->first();



            if(!$teacher){

                return response()->json([

                    'success'=>false,

                    'message'=>'Teacher profile not found.'

                ],404);

            }


            /*
            |--------------------------------------------------------------------------
            | CHECK TEACHER OWNS SCHEDULE
            |--------------------------------------------------------------------------
            */


            if($schedule->teacher_id != $teacher->id){


                return response()->json([

                    'success'=>false,

                    'message'=>
                    'You cannot create assignment for this schedule.'

                ],403);


            }


        }



        /*
        |--------------------------------------------------------------------------
        | CHECK DATA
        |--------------------------------------------------------------------------
        */


        if(!$schedule->course){

            return response()->json([

                'success'=>false,

                'message'=>'Course not found.'

            ],404);

        }



        if(!$schedule->studentClass){

            return response()->json([

                'success'=>false,

                'message'=>'Class not found.'

            ],404);

        }



        /*
        |--------------------------------------------------------------------------
        | GENERATE ASSIGNMENT CODE
        |--------------------------------------------------------------------------
        */


        $teacherName = strtoupper(

            preg_replace(
                '/[^A-Za-z]/',
                '',
                $teacher->first_name_english
            )

        );


        $courseCode = strtoupper(
            $schedule->course->course_code
        );


        $year = Carbon::now()->format('Y');


        $day = Carbon::now()->format('md');



        $sequence = Assignment::where(
                'schedule_id',
                $schedule->id
            )
            ->whereDate(
                'created_at',
                Carbon::today()
            )
            ->count()+1;



        $assignmentCode =

            "ASS-{$courseCode}-{$teacherName}-{$year}-{$day}-"
            .
            str_pad(
                $sequence,
                4,
                '0',
                STR_PAD_LEFT
            );



        /*
        |--------------------------------------------------------------------------
        | CREATE ASSIGNMENT
        |--------------------------------------------------------------------------
        */


        $assignment = Assignment::create([


            'assignment_code'
                => $assignmentCode,


            'schedule_id'
                => $schedule->id,


            'course_id'
                => $schedule->course_id,


            'class_id'
                => $schedule->class_id,


            'teacher_id'
                => $schedule->teacher_id,


            'assignment_type'
                => $request->assignment_type,


            'submission_type'
                => $request->submission_type,


            'title'
                => $request->title,


            'description'
                => $request->description,


            'due_date'
                => $request->due_date,


            'total_score'
                => $request->total_score,


            'status'
                => $request->status,


        ]);




        return response()->json([


            'success'=>true,


            'message'
                =>'Assignment created successfully.',


            'data'
                =>
                $assignment->load([

                    'course',

                    'teacher',

                    'schedule',

                    'class'

                ])
        ],201);
    }
    catch(\Exception $e){
        return response()->json([
            'success'=>false,
            'message'
                =>'Failed to create assignment.',
            'error'
                =>$e->getMessage(),


            'line'
                =>$e->getLine()



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
                'schedule_id' => 'required|exists:subject_schedules,id',
                // 'course_id' => 'nullable|exists:courses,id',
                // 'class_id' => 'nullable|exists:classes,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'total_score' => 'required|numeric|min:0',
                'status' => 'required|in:Open,Closed',
                'assignment_type' => 'required|in:Homework,Assignment,Quiz,Project,Orther',
                'submission_type' => 'required|in:Individual,Group',
            ]);

            $schedule = SubjectSchedule::with([
                'course',
                'teacher',
                'studentClass'
            ])->findOrFail($request->schedule_id);

            if (!$schedule->course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule course not found.'
                ], 404);
            }

            if (!$schedule->studentClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule class not found.'
                ], 404);
            }

            if (!$schedule->teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule teacher not found.'
                ], 404);
            }

            if ($request->course_id && $schedule->course_id != $request->course_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected schedule does not match the chosen course.'
                ], 422);
            }

            if ($request->class_id && $schedule->class_id != $request->class_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected schedule does not match the chosen class.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */
            if ($user->hasRole('Admin')) {
                $teacher = $schedule->teacher;
            }
            /*
            |--------------------------------------------------------------------------
            | TEACHER
            |--------------------------------------------------------------------------
            */
            else {
                $teacher = Teacher::where(
                    'user_id',
                    $user->id
                )
                ->first();

                if (!$teacher) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Teacher profile not found'
                    ], 404);
                }

                if ($assignment->teacher_id != $teacher->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot edit this assignment'
                    ], 403);
                }

                if ($schedule->teacher_id != $teacher->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected schedule is not assigned to this teacher.'
                    ], 403);
                }
            }
            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $assignment->update([
                'schedule_id' => $schedule->id,
                'course_id' => $schedule->course_id,
                'class_id' => $schedule->class_id,
                'teacher_id' => $schedule->teacher_id,
                'assignment_type' => $request->assignment_type,
                'submission_type' => $request->submission_type,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'total_score' => $request->total_score,
                'status' => $request->status,
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