<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignmentGroup;
use App\Models\AssignmentGroupMember;
use App\Models\Assignment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AssignmentGroupController extends Controller
{

    /**
     * ============================================
     * Display Groups
     * ============================================
     */
    public function index()
    {
        try {

            $groups = AssignmentGroup::with([
                'assignment.course',
                'assignment.teacher',
                'leader',
                'members.student'
            ])
            ->latest()
            ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $groups->items(),
                'pagination' => [
                    'current_page' => $groups->currentPage(),
                    'last_page'    => $groups->lastPage(),
                    'total'        => $groups->total(),
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],500);

        }
    }

    /**
     * ============================================
     * Create Assignment Group
     * ============================================
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // ==========================
            // VALIDATION
            // ==========================

            $request->validate([

                'assignment_id' =>
                    'required|exists:assignments,id',

                'group_name' =>
                    'required|string|max:100',

                'leader_student_id' =>
                    'required|exists:students,id',

                'members' =>
                    'required|array',

                'members.*' =>
                    'exists:students,id',

                'description' =>
                    'nullable|string',

            ]);



            // ==========================
            // CHECK ASSIGNMENT
            // ==========================

            $assignment = Assignment::findOrFail(
                $request->assignment_id
            );


            // Only Group Assignment

            if($assignment->submission_type != "Group"){

                return response()->json([

                    'success'=>false,

                    'message'=>'This assignment is Individual submission.'

                ],422);

            }



            // ==========================
            // DUPLICATE GROUP NAME
            // ==========================

            $exists = AssignmentGroup::where(
                    'assignment_id',
                    $request->assignment_id
                )
                ->where(
                    'group_name',
                    $request->group_name
                )
                ->exists();



            if($exists){

                return response()->json([

                    'success'=>false,

                    'message'=>'Group name already exists.'

                ],422);

            }



            // ==========================
            // CREATE GROUP
            // ==========================

            $group = AssignmentGroup::create([

                'assignment_id' =>
                    $request->assignment_id,

                'group_name' =>
                    $request->group_name,

                'leader_student_id' =>
                    $request->leader_student_id,

                'description' =>
                    $request->description,

                'status' =>
                    'Active',

            ]);




            // ==========================
            // INSERT LEADER
            // ==========================

            AssignmentGroupMember::create([

                'assignment_group_id' =>
                    $group->id,

                'student_id' =>
                    $request->leader_student_id,

                'role' =>
                    'leader',

                'status' =>
                    'active',

                'joined_at' =>
                    now(),

            ]);




            // ==========================
            // INSERT MEMBERS
            // ==========================

            foreach($request->members as $studentId){


                // Skip leader duplicate

                if($studentId == $request->leader_student_id){

                    continue;

                }



                AssignmentGroupMember::create([

                    'assignment_group_id' =>
                        $group->id,

                    'student_id' =>
                        $studentId,

                    'role' =>
                        'member',

                    'status' =>
                        'active',

                    'joined_at' =>
                        now(),

                ]);

            }



            // ==========================
            // COMMIT
            // ==========================

            DB::commit();



            return response()->json([

                'success'=>true,

                'message'=>'Assignment group created successfully.',


                'data'=>$group->load([

                    'assignment',

                    'leader',

                    'members.student'

                ])

            ],201);



        } catch (\Exception $e) {


            DB::rollBack();



            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }
    /**
     * ============================================
     * Show Group Detail
     * ============================================
     */
    public function show($id)
    {
        try {

            $group = AssignmentGroup::with([
                'assignment.course',
                'assignment.teacher',
                'leader',
                'members.student'
            ])->find($id);

            if (!$group) {

                return response()->json([
                    'success' => false,
                    'message' => 'Assignment group not found.'
                ],404);

            }

            return response()->json([
                'success'=>true,
                'data'=>$group
            ]);

        } catch (\Exception $e){

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],500);

        }
    }

    /**
     * ============================================
     * Update Group
     * ============================================
     */
    public function update(Request $request,$id)
    {
        DB::beginTransaction();

        try{

            $group = AssignmentGroup::find($id);

            if(!$group){

                return response()->json([
                    'success'=>false,
                    'message'=>'Assignment group not found.'
                ],404);

            }

            $request->validate([

                'group_name'=>'required|max:100',

                'leader_student_id'=>'required|exists:students,id',

                'description'=>'nullable|string',

                'status'=>'required|in:Active,Closed'

            ]);

            // Prevent duplicate name
            $exists = AssignmentGroup::where(
                    'assignment_id',
                    $group->assignment_id
                )
                ->where(
                    'group_name',
                    $request->group_name
                )
                ->where(
                    'id',
                    '!=',
                    $group->id
                )
                ->exists();

            if($exists){

                return response()->json([
                    'success'=>false,
                    'message'=>'Group name already exists.'
                ],422);

            }

            $group->update([

                'group_name'=>$request->group_name,

                'leader_student_id'=>$request->leader_student_id,

                'description'=>$request->description,

                'status'=>$request->status,

            ]);

            // Update leader role
            AssignmentGroupMember::where(
                'assignment_group_id',
                $group->id
            )->update([
                'role'=>'member'
            ]);

            AssignmentGroupMember::where(
                'assignment_group_id',
                $group->id
            )
            ->where(
                'student_id',
                $request->leader_student_id
            )
            ->update([
                'role'=>'leader'
            ]);

            DB::commit();

            return response()->json([

                'success'=>true,

                'message'=>'Group updated successfully.',

                'data'=>$group->load([
                    'leader',
                    'members.student',
                    'assignment'
                ])

            ]);

        }catch(\Exception $e){

            DB::rollBack();

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],500);

        }

    }

    /**
     * ============================================
     * Delete Group
     * ============================================
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try{

            $group = AssignmentGroup::find($id);

            if(!$group){

                return response()->json([
                    'success'=>false,
                    'message'=>'Group not found.'
                ],404);

            }

            AssignmentGroupMember::where(
                'assignment_group_id',
                $group->id
            )->delete();

            $group->delete();

            DB::commit();

            return response()->json([

                'success'=>true,

                'message'=>'Assignment group deleted successfully.'

            ]);

        }catch(\Exception $e){

            DB::rollBack();

            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }
    /**
     * ============================================
     * Add Members Into Group
     * ============================================
     */
    public function addMembers(Request $request,$id)
    {
        DB::beginTransaction();

        try{

            $group = AssignmentGroup::with('members')
                ->find($id);

            if(!$group){

                return response()->json([
                    'success'=>false,
                    'message'=>'Group not found.'
                ],404);

            }

            $request->validate([
                'student_ids'=>'required|array|min:1',
                'student_ids.*'=>'exists:students,id'
            ]);

            foreach($request->student_ids as $studentId){

                // already in group
                $exists = AssignmentGroupMember::where(
                    'assignment_group_id',
                    $group->id
                )
                ->where(
                    'student_id',
                    $studentId
                )
                ->exists();

                if($exists){
                    continue;
                }

                // already belongs to another group
                $anotherGroup = AssignmentGroupMember::where(
                    'student_id',
                    $studentId
                )
                ->whereHas('group',function($q) use($group){

                    $q->where(
                        'assignment_id',
                        $group->assignment_id
                    );

                })
                ->exists();

                if($anotherGroup){

                    continue;

                }

                AssignmentGroupMember::create([

                    'assignment_group_id'=>$group->id,

                    'student_id'=>$studentId,

                    'role'=>'member',

                    'status'=>'active',

                    'joined_at'=>now()

                ]);

            }

            DB::commit();

            return response()->json([

                'success'=>true,

                'message'=>'Members added successfully.',

                'data'=>$group->fresh()->load([
                    'members.student'
                ])

            ]);

        }catch(\Exception $e){

            DB::rollBack();

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],500);

        }

    }
    /**
     * ============================================
     * Available Students
     * ============================================
     */
    public function availableStudents($assignmentId)
    {

       try {

            // =====================================
            // Check Assignment
            // =====================================

            $assignment = Assignment::find($assignmentId);


            if(!$assignment){

                return response()->json([

                    'success' => false,

                    'message' => 'Assignment not found.'

                ],404);

            }



            // =====================================
            // Students already joined this assignment
            // =====================================

            $joinedStudentIds = AssignmentGroupMember::whereHas(

                'group',

                function($query) use ($assignmentId){

                    $query->where(
                        'assignment_id',
                        $assignmentId
                    );

                }

            )
            ->pluck('student_id');





            // =====================================
            // Get Available Students
            // =====================================

            $students = Student::whereNotIn(

                    'id',

                    $joinedStudentIds

                )
                ->select(

                    'id',

                    'student_code',

                    'first_name_english',

                    'last_name_english'

                )
                ->orderBy(
                    'first_name_english'
                )
                ->get();





            return response()->json([

                'success' => true,

                'data' => $students

            ]);



        } catch(\Exception $e){


            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ],500);


        }
    }
}