<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index() {
        $teachers = Teacher::with(['department'])
        ->orderBy('id', 'desc')
        ->paginate(10);


        return response()->json([
            'success' => true,
            'data' => $teachers->items(),
            'pagination' => [
                'total'        => $teachers->total(),
                'count'        => $teachers->count(),
                'per_page'     => $teachers->perPage(),
                'current_page' => $teachers->currentPage(),
                'total_pages'  => $teachers->lastPage()
            ]
        ], 200);
    }
    public function getFormDataDependencies(){
        $departments = Department::orderBy('department_name_english')->get();
        return response()->json([
            'success' => true,
            'departments' => $departments
        ], 200);
    }

    public function store(Request $request){
        $request->validate([
            'teacher_code'        => 'required|unique:teachers,teacher_code',
            'department_id'       => 'required|exists:departments,id',
            'first_name_khmer'    => 'required|string|max:255',
            'last_name_khmer'     => 'required|string|max:255',
            'first_name_english'  => 'required|string|max:255',
            'last_name_english'   => 'required|string|max:255',
            'gender'              => 'required|in:Male,Female',
            'date_of_birth'       => 'nullable|date',
            'phone'               => 'nullable|string|max:30',
            'email'               => 'nullable|email|unique:teachers,email',
            'address'             => 'nullable|string',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hire_date'           => 'nullable|date',
        ]);

        $photo = null;

         if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('teachers', 'public');
        }

        $teacher = Teacher::create([
            'teacher_code'        => $request-> teacher_code,
            'department_id'       => $request-> department_id,
            'first_name_khmer'    => $request-> first_name_khmer,
            'last_name_khmer'     => $request-> last_name_khmer,
            'first_name_english'  => $request-> first_name_english,
            'last_name_english'   => $request-> last_name_english,
            'gender'              => $request-> gender,
            'date_of_birth'       => $request-> date_of_birth,
            'phone'               => $request-> phone,
            'email'               => $request-> email,
            'address'             => $request-> address,
            'photo'               => $request-> photo,
            'hire_date'           => $request-> hire_date,
            'status'              => 'Inactive',
        ]);

        return response()->json([
            'success'=> true,
            'message' => 'Teacher registered successfully.',
            'data' => $teacher
        ], 201);
    }
    public function show(string $id){
        $teacher = Teacher::with(['department'])->find($id);

        if(!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher record not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $teacher
        ], 200);
    }

    public function update(Request $request,string $id){
        try {

            $teacher = Teacher::find($id);



            if(!$teacher){

                return response()->json([

                    'success'=>false,

                    'message'=>'Teacher record not found.'

                ],404);

            }




            // Find user only after teacher exists

            $user = null;

            if($teacher->user_id){

                $user = User::find($teacher->user_id);

            }




            $request->validate([


                'teacher_code' =>
                'required|unique:teachers,teacher_code,' . $teacher->id,


                'department_id' =>
                'required|exists:departments,id',


                'first_name_khmer' =>
                'required|string|max:255',


                'last_name_khmer' =>
                'required|string|max:255',


                'first_name_english' =>
                'required|string|max:255',


                'last_name_english' =>
                'required|string|max:255',


                'gender' =>
                'required|in:Male,Female',


                'date_of_birth' =>
                'nullable|date',


                'phone' =>
                'nullable|string|max:30',


                'email' =>
                'nullable|email|unique:teachers,email,' . $teacher->id,


                'address' =>
                'nullable|string',


                'photo' =>
                'nullable|image|mimes:jpg,jpeg,png|max:2048',


                'hire_date' =>
                'nullable|date',


                'status' =>
                'required|in:Active,Inactive',


            ]);





            // ==========================
            // UPDATE PHOTO
            // ==========================


            if($request->hasFile('photo')){


                if(
                    $teacher->photo &&
                    Storage::disk('public')
                    ->exists($teacher->photo)
                ){

                    Storage::disk('public')
                    ->delete($teacher->photo);

                }



                $teacher->photo =
                $request->file('photo')
                ->store(
                    'teachers',
                    'public'
                );

            }





            // ==========================
            // UPDATE TEACHER
            // ==========================


            $teacher->update([


                'teacher_code' =>
                $request->teacher_code,


                'department_id' =>
                $request->department_id,


                'first_name_khmer' =>
                $request->first_name_khmer,


                'last_name_khmer' =>
                $request->last_name_khmer,


                'first_name_english' =>
                $request->first_name_english,


                'last_name_english' =>
                $request->last_name_english,


                'gender' =>
                $request->gender,


                'date_of_birth' =>
                $request->date_of_birth,


                'phone' =>
                $request->phone,


                'email' =>
                $request->email,


                'address' =>
                $request->address,


                'hire_date' =>
                $request->hire_date,


                'status' =>
                $request->status,


            ]);






            // ==========================
            // UPDATE USER ACCOUNT
            // ONLY IF ACTIVATED
            // ==========================


            if($user){


                $user->update([


                    'username' =>

                    $teacher->first_name_english
                    .' '.
                    $teacher->last_name_english,


                    'email' =>

                    $teacher->email,


                ]);

            }





            return response()->json([


                'success'=>true,


                'message'=>

                'Teacher profile updated successfully.',



                'data'=>

                $teacher->load([

                    'department',

                    'user'

                ])


            ],200);




        }catch(\Exception $e){


            return response()->json([


                'success'=>false,


                'message'=>

                'Failed to update teacher.',



                'error'=>

                $e->getMessage()


            ],500);


        }

    }

    public function destroy(string $id){
        $teacher = Teacher::find($id);

        if(!$teacher){
            return response()->json([
                'success' => false,
                'message' => 'Teacher record not found.'
            ], 400);
        }
        // Delete photo asset file from disk space
        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher registry profile removed successfully.'
        ], 200);
    }
        /**
     * Teacher Dropdown API
     */
    public function dropdown()
    {
        try {

            $teachers = Teacher::select(
                    'id',
                    'teacher_code',
                    'first_name_english',
                    'last_name_english'
                )
                ->where('status', 'Active')
                ->orderBy('first_name_english')
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'teacher_code' => $teacher->teacher_code,
                        'full_name_english' =>
                            $teacher->first_name_english . ' ' .
                            $teacher->last_name_english,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Teachers loaded successfully.',
                'data' => $teachers,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to load teachers.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }
}
