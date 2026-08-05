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
    public function dashboard()
    {
        try {


            $user = auth()->user();


            $assignmentQuery = Assignment::query();



            // Teacher dashboard

            if($user->hasRole('Teacher')){


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



                $assignmentQuery->where(
                    'teacher_id',
                    $teacher->id
                );

            }



            // Admin can see all

            if($user->hasRole('Admin')){


                $assignmentQuery;


            }





            $assignments =
            $assignmentQuery->pluck('id');



            return response()->json([


                'success'=>true,


                'data'=>[


                    'total_assignments'=>
                    $assignments->count(),



                    'total_submissions'=>
                    AssignmentSubmission::whereIn(
                        'assignment_id',
                        $assignments
                    )->count(),



                    'graded'=>
                    AssignmentSubmission::whereIn(
                        'assignment_id',
                        $assignments
                    )
                    ->where(
                        'status',
                        'Graded'
                    )
                    ->count(),



                    'pending'=>
                    AssignmentSubmission::whereIn(
                        'assignment_id',
                        $assignments
                    )
                    ->where(
                        'status',
                        'Submitted'
                    )
                    ->count(),



                    'late'=>
                    AssignmentSubmission::whereIn(
                        'assignment_id',
                        $assignments
                    )
                    ->where(
                        'status',
                        'Late'
                    )
                    ->count()

                ]

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }
    }
    public function grade(Request $request, $id)
    {
        try {
            $user = auth()->user();

            // ==========================================
            // Only Admin or Teacher
            // ==========================================

            if (
                !$user->hasRole('Admin') &&
                !$user->hasRole('Teacher')
            ) {

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 403);

            }

            // ==========================================
            // Find Submission
            // ==========================================

            $submission = AssignmentSubmission::with([

                'assignment.course',
                'assignment.teacher',

                'student',

                'group',
                'group.leader',
                'group.members.student',

                'submitter',

                'grader'

            ])->find($id);

            if (!$submission) {

                return response()->json([
                    'success' => false,
                    'message' => 'Submission not found.'
                ], 404);

            }

            // ==========================================
            // Assignment must still exist
            // ==========================================

            if (!$submission->assignment) {

                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found.'
                ], 404);

            }

            // ==========================================
            // Teacher Permission
            // ==========================================

            $teacher = null;

            if ($user->hasRole('Teacher')) {

                $teacher = Teacher::where(
                    'user_id',
                    $user->id
                )->first();

                if (!$teacher) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Teacher not found.'
                    ], 404);

                }

                if (
                    $submission->assignment->teacher_id !=
                    $teacher->id
                ) {

                    return response()->json([
                        'success' => false,
                        'message' =>
                        'You can only grade your own assignments.'
                    ], 403);

                }

            }

            // ==========================================
            // Already Graded?
            // ==========================================

            if ($submission->status == 'Graded') {

                return response()->json([
                    'success' => false,
                    'message' => 'This submission has already been graded.'
                ], 422);

            }

            // ==========================================
            // Validation
            // ==========================================

            $request->validate([

                'score' => 'required|numeric|min:0|max:100',

                'feedback' => 'nullable|string|max:2000'

            ]);

            // ==========================================
            // Ready For Update
            // =========================================='
                    // ==========================================
            // Update Grade
            // ==========================================

            $submission->update([

                'score'      => $request->score,

                'feedback'   => $request->feedback,

                'status'     => 'Graded',

                // Save teacher ID if graded by teacher
                'graded_by'  => $teacher ? $teacher->id : null,

                'graded_at'  => now(),

            ]);

            // ==========================================
            // Reload Relationships
            // ==========================================

            $submission = $submission->fresh()->load([

                'assignment.course',

                'assignment.teacher',

                'student',

                'group',

                'group.leader',

                'group.members.student',

                'submitter',

                'grader'

            ]);

            // ==========================================
            // Success Response
            // ==========================================

            return response()->json([

                'success' => true,

                'message' => 'Submission graded successfully.',

                'data' => $submission

            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([

                'success' => false,

                'message' => 'Validation failed.',

                'errors' => $e->errors()

            ], 422);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);

        }

    }
    /**
     * Display submissions
     */
    public function index(Request $request)
    {
        try {

            $user = auth()->user();


            $query = AssignmentSubmission::with([

                'assignment.course',
                'assignment.class',
                'assignment.teacher',

                'student',

                'group',
                'group.leader',
                'group.members.student',

                'submitter',
                'grader'

            ]);


            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */

            if ($user->hasRole('Admin')) {

                $query->latest();

            }


            /*
            |--------------------------------------------------------------------------
            | TEACHER
            |--------------------------------------------------------------------------
            */

            elseif ($user->hasRole('Teacher')) {


                $teacher = Teacher::where(
                    'user_id',
                    $user->id
                )->first();



                if (!$teacher) {

                    return response()->json([

                        'success'=>false,

                        'message'=>'Teacher not found'

                    ],404);

                }



                // Teacher only see own assignment submissions

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



            /*
            |--------------------------------------------------------------------------
            | STUDENT
            |--------------------------------------------------------------------------
            */

            elseif ($user->hasRole('Student')) {


                $student = Student::where(
                    'user_id',
                    $user->id
                )->first();



                if (!$student) {

                    return response()->json([

                        'success'=>false,

                        'message'=>'Student not found'

                    ],404);

                }



                $query->where(function($q) use($student){


                    // Individual

                    $q->where(
                        'student_id',
                        $student->id
                    )


                    // Group

                    ->orWhereHas(

                        'group.members',

                        function($member) use($student){

                            $member->where(
                                'student_id',
                                $student->id
                            );

                        }

                    );


                });


            }



            /*
            |--------------------------------------------------------------------------
            | FILTER CLASS
            |--------------------------------------------------------------------------
            */

            if($request->filled('class_id')){


                $query->whereHas(

                    'assignment',

                    function($assignment) use($request){

                        $assignment->where(

                            'class_id',

                            $request->class_id

                        );

                    }

                );


            }



            /*
            |--------------------------------------------------------------------------
            | FILTER COURSE
            |--------------------------------------------------------------------------
            */

            if($request->filled('course_id')){


                $query->whereHas(

                    'assignment',

                    function($assignment) use($request){


                        $assignment->where(

                            'course_id',

                            $request->course_id

                        );


                    }

                );


            }



            /*
            |--------------------------------------------------------------------------
            | FILTER DATE
            |--------------------------------------------------------------------------
            */

            if($request->filled('date')){


                $query->whereDate(

                    'submitted_at',

                    $request->date

                );


            }




            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if($request->filled('search')){


                $search = $request->search;



                $query->where(function($q) use($search){



                    // Assignment title

                    $q->whereHas(

                        'assignment',

                        function($assignment) use($search){


                            $assignment->where(

                                'title',

                                'like',

                                "%{$search}%"

                            )

                            // Search class

                            ->orWhereHas(

                                'class',

                                function($class) use($search){


                                    $class->where(

                                        'class_name',

                                        'like',

                                        "%{$search}%"

                                    );


                                }

                            )

                            // Search course

                            ->orWhereHas(

                                'course',

                                function($course) use($search){


                                    $course->where(

                                        'course_name',

                                        'like',

                                        "%{$search}%"

                                    );


                                }

                            );


                        }

                    )



                    // Search student

                    ->orWhereHas(

                        'student',

                        function($student) use($search){


                            $student->where(

                                'first_name_english',

                                'like',

                                "%{$search}%"

                            )

                            ->orWhere(

                                'last_name_english',

                                'like',

                                "%{$search}%"

                            );


                        }

                    );


                });


            }





            /*
            |--------------------------------------------------------------------------
            | FILTER STATUS
            |--------------------------------------------------------------------------
            */

            if($request->filled('status')){


                $query->where(

                    'status',

                    $request->status

                );


            }





            /*
            |--------------------------------------------------------------------------
            | PAGINATION
            |--------------------------------------------------------------------------
            */

            $submissions = $query

                ->latest()

                ->paginate(

                    $request->per_page ?? 10

                );





            return response()->json([


                'success'=>true,


                'message'=>'Submissions loaded successfully',


                'data'=>$submissions->items(),



                'pagination'=>[


                    'current_page'=>$submissions->currentPage(),


                    'last_page'=>$submissions->lastPage(),


                    'per_page'=>$submissions->perPage(),


                    'total'=>$submissions->total()


                ]


            ],200);



        } catch(\Exception $e){



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
        try {

            $user = auth()->user();

            // Find logged in student
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

            // Load assignments only for student's class
            $assignments = Assignment::with([
                    'course',
                    'teacher',
                    'class'
                ])
                ->where('status', 'Open')
                ->where('class_id', $student->class_id)
                ->whereDate('due_date', '>=', Carbon::today())
                ->orderBy('due_date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $assignments,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

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
            $lastSubmission = AssignmentSubmission::latest('id')->first();

            $nextNumber = $lastSubmission
                ? $lastSubmission->id + 1
                : 1;

            $submissionCode =
                'SUB-' .
                date('Y') .
                '-' .
                str_pad(
                    $nextNumber,
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
        try {
            $user = auth()->user();
            $submission = AssignmentSubmission::with([

                'assignment.course',

                'assignment.teacher',

                'student',

                'group',

                'group.leader',

                'group.members.student',

                'submitter'

            ])
            ->find($id);



            if(!$submission){

                return response()->json([

                    'success'=>false,

                    'message'=>'Submission not found.'

                ],404);

            }



            // ==================================
            // ADMIN
            // ==================================

            if($user->hasRole('Admin')){


                return response()->json([

                    'success'=>true,

                    'data'=>$submission

                ]);

            }



            // ==================================
            // TEACHER
            // ==================================

            if($user->hasRole('Teacher')){


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



                if(
                    $submission->assignment->teacher_id 
                    != 
                    $teacher->id
                ){

                    return response()->json([

                        'success'=>false,

                        'message'=>'You cannot view this submission.'

                    ],403);

                }



                return response()->json([

                    'success'=>true,

                    'data'=>$submission

                ]);

            }



            // ==================================
            // STUDENT
            // ==================================

            if($user->hasRole('Student')){


                $student = Student::where(
                    'user_id',
                    $user->id
                )->first();



                if(!$student){

                    return response()->json([

                        'success'=>false,

                        'message'=>'Student not found.'

                    ],404);

                }



                $allowed = false;



                // Individual submission

                if(
                    $submission->student_id 
                    == 
                    $student->id
                ){

                    $allowed = true;

                }



                // Group submission

                if($submission->group_id){


                    $isMember = AssignmentGroupMember::where(

                        'assignment_group_id',

                        $submission->group_id

                    )
                    ->where(

                        'student_id',

                        $student->id

                    )
                    ->exists();



                    if($isMember){

                        $allowed = true;

                    }

                }



                if(!$allowed){

                    return response()->json([

                        'success'=>false,

                        'message'=>'You cannot view this submission.'

                    ],403);

                }



                return response()->json([

                    'success'=>true,

                    'data'=>$submission

                ]);

            }



            return response()->json([

                'success'=>false,

                'message'=>'Unauthorized.'

            ],403);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }


    /**
     * Update Student Submission
     */
    public function update(Request $request,$id)
    {
        try {

            $user = auth()->user();

            $submission = AssignmentSubmission::with([
                'group.members'
            ])->find($id);

            if (!$submission) {

                return response()->json([
                    'success' => false,
                    'message' => 'Submission not found.'
                ], 404);

            }

            // ==========================
            // Only Student
            // ==========================

            if (!$user->hasRole('Student')) {

                return response()->json([
                    'success' => false,
                    'message' => 'Only students can update submissions.'
                ], 403);

            }

            $student = Student::where(
                'user_id',
                $user->id
            )->first();

            if (!$student) {

                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);

            }

            // ==========================
            // Cannot update after grading
            // ==========================

            if ($submission->status == 'Graded') {

                return response()->json([
                    'success' => false,
                    'message' => 'This submission has already been graded.'
                ], 403);

            }

            // ==========================
            // Individual Submission
            // ==========================

            if ($submission->student_id) {

                if ($submission->student_id != $student->id) {

                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot update this submission.'
                    ], 403);

                }

            }

            // ==========================
            // Group Submission
            // ==========================

            if ($submission->group_id) {

                $leader = AssignmentGroupMember::where(
                    'assignment_group_id',
                    $submission->group_id
                )
                ->where(
                    'student_id',
                    $student->id
                )
                ->where(
                    'role',
                    'leader'
                )
                ->first();

                if (!$leader) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Only the group leader can update this submission.'
                    ], 403);

                }

            }

            // ==========================
            // Validation
            // ==========================

            $request->validate([

                'content' => 'nullable|string',

                'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar,jpg,jpeg,png|max:10240'

            ]);

            // ==========================
            // Replace File
            // ==========================

            if ($request->hasFile('file')) {

                if (
                    $submission->file_path &&
                    Storage::disk('public')->exists($submission->file_path)
                ) {

                    Storage::disk('public')->delete($submission->file_path);

                }

                $submission->file_path = $request
                    ->file('file')
                    ->store('assignment_submissions', 'public');

            }

            // ==========================
            // Update Content
            // ==========================

            if ($request->filled('content')) {

                $submission->content = $request->content;

            }

            // Update submission time

            $submission->submitted_at = now();

            // If resubmitted after being late, keep status as Late.
            // Otherwise keep Submitted.

            if ($submission->status != 'Late') {

                $submission->status = 'Submitted';

            }

            $submission->save();

            return response()->json([

                'success' => true,

                'message' => 'Submission updated successfully.',

                'data' => $submission->fresh()->load([

                    'assignment.course',
                    'assignment.teacher',
                    'student',
                    'group',
                    'group.members.student',
                    'submitter'

                ])

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);

        }
    }
    /**
     * Delete
     */
    public function destroy($id)
    {

        try {

                $user = auth()->user();

                $submission = AssignmentSubmission::find($id);

                if (!$submission) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Submission not found.'
                    ], 404);

                }

                // ==================================
                // Cannot delete after grading
                // ==================================

                if ($submission->status == 'Graded') {

                    return response()->json([
                        'success' => false,
                        'message' => 'Graded submission cannot be deleted.'
                    ], 403);

                }

                // ==================================
                // ADMIN
                // ==================================

                if (!$user->hasRole('Admin')) {

                    // Only students can delete their own submission
                    if (!$user->hasRole('Student')) {

                        return response()->json([
                            'success' => false,
                            'message' => 'Unauthorized.'
                        ], 403);

                    }

                    $student = Student::where(
                        'user_id',
                        $user->id
                    )->first();

                    if (!$student) {

                        return response()->json([
                            'success' => false,
                            'message' => 'Student not found.'
                        ], 404);

                    }

                    // ==================================
                    // Individual Submission
                    // ==================================

                    if ($submission->student_id) {

                        if ($submission->student_id != $student->id) {

                            return response()->json([
                                'success' => false,
                                'message' => 'You cannot delete this submission.'
                            ], 403);

                        }

                    }

                    // ==================================
                    // Group Submission
                    // ==================================

                    if ($submission->group_id) {

                        $leader = AssignmentGroupMember::where(
                            'assignment_group_id',
                            $submission->group_id
                        )
                        ->where(
                            'student_id',
                            $student->id
                        )
                        ->where(
                            'role',
                            'leader'
                        )
                        ->exists();

                        if (!$leader) {

                            return response()->json([
                                'success' => false,
                                'message' => 'Only the group leader can delete this submission.'
                            ], 403);

                        }

                    }

                }

                // ==================================
                // Delete Uploaded File
                // ==================================

                if (
                    $submission->file_path &&
                    Storage::disk('public')->exists($submission->file_path)
                ) {

                    Storage::disk('public')->delete(
                        $submission->file_path
                    );

                }

                // ==================================
                // Delete Submission
                // ==================================

                $submission->delete();

                return response()->json([

                    'success' => true,

                    'message' => 'Submission deleted successfully.'

                ]);

            } catch (\Exception $e) {

                return response()->json([

                    'success' => false,

                    'message' => $e->getMessage()

                ], 500);

            }

    }
    public function byAssignment($assignment_id)
{

    try {


        $submissions =
        AssignmentSubmission::where(
            'assignment_id',
            $assignment_id
        )
        ->with([

            'student',

            'assignment.course',

            'assignment.teacher'

        ])
        ->latest()
        ->get();



        return response()->json([

            'success'=>true,

            'data'=>$submissions

        ]);



    } catch(\Exception $e){


        return response()->json([

            'success'=>false,

            'message'=>$e->getMessage()

        ],500);


    }

}


}