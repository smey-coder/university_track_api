<?php
namespace App\Http\Controllers\Api\Web_api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Teacher;
use Carbon\Carbon;
class AssignmentController extends Controller
{
    /**
     * Display assignments
     */
    public function index()
    {
        try {
            $user = auth()->user();

            $query = Assignment::with('course','teacher');

            if ($user->hasRole('Teacher')) {

                $teacher = Teacher::where('user_id',$user->id)->first();

                if ($teacher) {
                    $query->where('teacher_id',$teacher->id);
                }

            }

            if ($user->hasRole('Student')) {

                // filter assignments for student's class/course
            }

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
        $user = auth()->user();

        // ==========================
        // ADMIN
        // ==========================
        if ($user->hasRole('Admin')) {

            $courses = Course::where('status', 'Active')
                ->select(
                    'id',
                    'course_code',
                    'course_name',
                    'teacher_id'
                )
                ->orderBy('course_name')
                ->get();

            $teachers = Teacher::where('status', 'Active')
                ->select(
                    'id',
                    'first_name_english',
                    'last_name_english'
                )
                ->orderBy('first_name_english')
                ->get();

            return response()->json([
                'success' => true,
                'courses' => $courses,
                'teachers' => $teachers
            ]);
        }

        // ==========================
        // TEACHER
        // ==========================

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found.'
            ],404);
        }

        $courses = Course::where('teacher_id', $teacher->id)
            ->where('status','Active')
            ->select(
                'id',
                'course_code',
                'course_name'
            )
            ->orderBy('course_name')
            ->get();

        return response()->json([
            'success' => true,
            'courses' => $courses,
            'teachers' => [
                [
                    'id' => $teacher->id,
                    'first_name_english' => $teacher->first_name_english,
                    'last_name_english' => $teacher->last_name_english,
                ]
            ]
        ]);
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

                // Admin only
                'teacher_id'   => 'nullable|exists:teachers,id',
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
                'teacher'
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
                =>'required|in:Open,Closed'


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

}