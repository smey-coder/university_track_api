<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignmentSubmission;
use App\Models\Assignment;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class AssignmentSubmissionController extends Controller
{


    public function grade(Request $request, $id)
    {
        try {

            $user = auth()->user();
            // ==========================
            // FIND SUBMISSION
            // ==========================

            $submission = AssignmentSubmission::with([
                'assignment.course',
                'assignment.teacher',
                'student',
                'group',
                'group.members.student'
            ])
            ->find($id);
            if(!$submission){
                return response()->json([
                    'success'=>false,
                    'message'=>'Submission not found'
                ],404);

            }
            // ==========================
            // CHECK ROLE
            // ==========================
            if($user->hasRole('Teacher')){
                $teacher = Teacher::where(
                    'user_id',
                    $user->id
                )->first();



                if(!$teacher){

                    return response()->json([

                        'success'=>false,

                        'message'=>'Teacher not found'

                    ],404);

                }
                // Teacher can grade only own assignment

                if(
                    $submission->assignment->teacher_id 
                    != 
                    $teacher->id
                ){

                    return response()->json([

                        'success'=>false,

                        'message'=>
                        'You cannot grade this submission'

                    ],403);

                }
            }

            // ==========================
            // VALIDATION
            // ==========================

            $request->validate([

                'score'=>'required|numeric|min:0|max:100',

                'feedback'=>'nullable|string'

            ]);

            // ==========================
            // UPDATE GRADE
            // ==========================
            $submission->update([
                'score'=>$request->score,
                'feedback'=>$request->feedback,
                'status'=>'Graded'
            ]);
            return response()->json([
                'success'=>true,
                'message'=>
                'Submission graded successfully',
                'data'=>
                $submission->fresh()->load([

                    'assignment.course',

                    'assignment.teacher',

                    'student'

                ])


            ]);
        }catch(\Exception $e){
            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }
    /**
     * Display submissions
     */
    public function index()
    {

        try {

            $user = auth()->user();
            $query = AssignmentSubmission::with([
                'assignment.course',
                'assignment.teacher',
                'student'

            ]);

            // ==========================
            // ADMIN
            // ==========================
            if($user->hasRole('Admin')){
                $query->latest();
            }
            // ==========================
            // TEACHER
            // ==========================

            elseif($user->hasRole('Teacher')){
                $teacher =
                Teacher::where(
                    'user_id',
                    $user->id
                )->first();
                if(!$teacher){
                    return response()->json([
                        'success'=>false,
                        'message'=>'Teacher not found'
                    ],404);

                }
                $query->whereHas(

                    'assignment',

                    function($q) use($teacher){
                        $q->where(

                            'teacher_id',

                            $teacher->id
                        );
                    }
                );
            }
            // ==========================
            // STUDENT
            // ==========================

            else{
                $student =
                Student::where(
                    'user_id',
                    $user->id

                )->first();
                if(!$student){
                    return response()->json([

                        'success'=>false,

                        'message'=>'Student not found'

                    ],404);

                }
                $query->where(

                    'student_id',

                    $student->id

                );
            }

            $submissions =
            $query->paginate(10);
            return response()->json([
                'success'=>true,
                'data'=>
                $submissions->items(),
                'pagination'=>[
                    'current_page'=>
                    $submissions->currentPage(),
                    'last_page'=>
                    $submissions->lastPage(),
                    'total'=>
                    $submissions->total()
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
     * Student available assignments
     */
    public function available()
    {
        try{
            $assignments = Assignment::with([
                'course',
                'teacher'
            ])
            ->where('status','Open')
            ->whereDate(
                'due_date',
                '>=',
                Carbon::today()
            )
            ->get();
            return response()->json([
                'success'=>true,
                'data'=>$assignments,
            ]);
        }catch(\Exception $e){

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()

            ],500);
        }
    }
    private function generateSubmissionCode($studentId)
    {
        $year = date('Y');
        $count = AssignmentSubmission::count() + 1;
        return "SUB-"
            .$year
            ."-"
            .str_pad(
                $count,
                5,
                '0',
                STR_PAD_LEFT
            );
    }

    /**
     * Student Submit Assignment
     */
    public function store(Request $request)
    {
         try {
            $user = auth()->user();

            // ==========================
            // Get Student
            // ==========================
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

            // ==========================
            // Validate
            // ==========================
            $request->validate([
                'assignment_id' => 'required|exists:assignments,id',
                'content'       => 'nullable|string',
                'file'          => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar,jpg,jpeg,png|max:10240',
            ]);

            // ==========================
            // Find Assignment
            // ==========================
            $assignment = Assignment::findOrFail($request->assignment_id);

            // Assignment must still be open
            if ($assignment->status == "Closed") {
                return response()->json([
                    'success' => false,
                    'message' => 'This assignment is already closed.'
                ], 403);
            }

            // ==========================
            // CHECK DUPLICATE SUBMISSION
            // ==========================

            $existingSubmission = AssignmentSubmission::where(
                    'assignment_id',
                    $request->assignment_id
                )
                ->where(
                    'student_id',
                    $student->id
                )
                ->first();



            if($existingSubmission){

                return response()->json([

                    'success'=>false,

                    'message'=>'You already submitted this assignment. You cannot submit again.'

                ],422);

            }

            // ==========================
            // Upload File
            // ==========================
            $filePath = null;

            if ($request->hasFile('file')) {
                $filePath = $request->file('file')
                            ->store('assignment_submissions', 'public');
            }

            // ==========================
            // Submission Status
            // ==========================
            $status = Carbon::today()->gt(Carbon::parse($assignment->due_date))
                ? 'Late'
                : 'Submitted';

            // ==========================
            // Generate Submission Code
            // Example:
            // SUB-2026-00001
            // ==========================
            $submissionCode =
                'SUB-' .
                date('Y') .
                '-' .
                str_pad(
                    AssignmentSubmission::count() + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                );

            // ==========================
            // Create Submission
            // ==========================
            $submission = AssignmentSubmission::create([

                'submission_code' => $submissionCode,

                'assignment_id' => $assignment->id,

                'student_id' => $student->id,

                'file_path' => $filePath,

                'content' => $request->content,

                'submitted_at' => now(),

                'status' => $status,

            ]);

            return response()->json([

                'success' => true,

                'message' => 'Assignment submitted successfully.',

                'data' => $submission->load([
                    'assignment.course',
                    'assignment.teacher',
                    'student'
                ])

            ], 201);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);

        }

    }

    /**
     * Show submission
     */
    public function show($id)
    {


        $submission =
        AssignmentSubmission::with([

            'assignment.course',

            'assignment.teacher',

            'student',
            'group',

            'group.leader',

            'group.members.student'


        ])

        ->find($id);



        if(!$submission){


            return response()->json([

                'success'=>false,

                'message'=>'Submission not found'

            ],404);


        }

        return response()->json([


            'success'=>true,


            'data'=>$submission


        ]);


    }


    /**
     * Update Student Submission
     */
    public function update(Request $request,$id)
    {
        try {
                $user = auth()->user();
                $submission = AssignmentSubmission::find($id);

                if(!$submission){
                    return response()->json([

                        'success'=>false,

                        'message'=>'Submission not found'

                    ],404);

                }

                // =========================
                // CHECK STUDENT OWNER
                // =========================

                if($user->hasRole('Student')){


                    $student = Student::where(
                        'user_id',
                        $user->id
                    )->first();



                    if(!$student || $submission->student_id != $student->id){

                        return response()->json([

                            'success'=>false,

                            'message'=>'You cannot update this submission'

                        ],403);

                    }

                }





                // =========================
                // CHECK GRADED
                // =========================

                if($submission->status == "Graded"){


                    return response()->json([

                        'success'=>false,

                        'message'=>'Cannot update graded submission'

                    ],403);


                }






                $request->validate([


                    'content'=>'nullable|string',


                    'file'=>'nullable|file|max:5120'


                ]);







                // =========================
                // UPDATE FILE
                // =========================

                if($request->hasFile('file')){



                    // Delete old file

                    if(
                        $submission->file_path &&
                        Storage::disk('public')
                        ->exists($submission->file_path)
                    ){

                        Storage::disk('public')
                        ->delete(
                            $submission->file_path
                        );

                    }






                    // Store new file

                    $submission->file_path =

                    $request->file('file')
                    ->store(
                        'assignment_submissions',
                        'public'
                    );


                }







                // =========================
                // UPDATE CONTENT
                // =========================


                if($request->has('content')){


                    $submission->content =
                    $request->content;


                }







                // Update submit time

                $submission->submitted_at = now();



                $submission->save();







                return response()->json([


                    'success'=>true,


                    'message'=>
                    'Submission updated successfully',



                    'data'=>
                    $submission->load([

                        'assignment.course',

                        'assignment.teacher',

                        'student'

                    ])



                ]);





            }catch(\Exception $e){


                return response()->json([


                    'success'=>false,


                    'message'=>$e->getMessage()


                ],500);


            }

    }
    /**
     * Delete
     */
    public function destroy($id)
    {


        $submission =
        AssignmentSubmission::find($id);





        if(!$submission){


            return response()->json([


                'success'=>false,


                'message'=>'Submission not found'


            ],404);


        }





        if($submission->file){


            Storage::disk('public')

            ->delete(

                $submission->file

            );


        }
        $submission->delete();


        return response()->json([


            'success'=>true,


            'message'=>

            'Submission deleted successfully'


        ]);



    }



}