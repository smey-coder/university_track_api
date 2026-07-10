<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Department;
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
            'status'              => 'required|in:Active,Inactive',
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
            'status'              => $request-> status,
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
        $teacher = Teacher::find($id);
        if(!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher record not found.'
            ], 404);
        }

         $request->validate([
            'teacher_code'        => 'required|unique:teachers,teacher_code,' . $teacher->id,
            'department_id'       => 'required|exists:departments,id',
            'first_name_khmer'    => 'required|string|max:255',
            'last_name_khmer'     => 'required|string|max:255',
            'first_name_english'  => 'required|string|max:255',
            'last_name_english'   => 'required|string|max:255',
            'gender'              => 'required|in:Male,Female',
            'date_of_birth'       => 'nullable|date',
            'phone'               => 'nullable|string|max:30',
            'email'               => 'nullable|email|unique:teachers,email,' . $teacher->id,
            'address'             => 'nullable|string',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hire_date'           => 'nullable|date',
            'status'              => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo asset if it exists to preserve storage space
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $teacher->photo = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($request->except('photo'));

        return response()->json([
            'success' => true,
            'message' => 'Teacher profile updated successfully.',
            'data' => $teacher->load(['department'])
        ], 200);

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
}
