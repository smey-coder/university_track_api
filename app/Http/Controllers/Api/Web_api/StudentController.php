<?php
namespace App\Http\Controllers\Api\Web_api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of students (API Paginated).
     */
    public function index()
    {
        // Fetch students with related models, ordered by newest first
        $students = Student::with(['department', 'classes'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $students->items(),
            'pagination' => [
                'total'        => $students->total(),
                'count'        => $students->count(),
                'per_page'     => $students->perPage(),
                'current_page' => $students->currentPage(),
                'total_pages'  => $students->lastPage()
            ]
        ], 200);
    }

    /**
     * Get dependencies required to create/edit a student (Departments & Classes list).
     */
    public function getFormDataDependencies()
    {
        $departments = Department::orderBy('department_name_english')->get();
        $classes = StudentClass::orderBy('class_name')->get();

        return response()->json([
            'success' => true,
            'departments' => $departments,
            'classes' => $classes
        ], 200);
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_code'       => 'required|unique:students,student_code',
            'department_id'      => 'required|exists:departments,id',
            'class_id'           => 'required|exists:classes,id',
            'first_name_khmer'   => 'required',
            'last_name_khmer'    => 'required',
            'first_name_english' => 'required',
            'last_name_english'  => 'required',
            'gender'             => 'required|in:Male,Female',
            'date_of_birth'      => 'nullable|date',
            'phone'              => 'nullable',
            'email'              => 'nullable|email|unique:students,email',
            'address'            => 'nullable',
            'photo'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'enrollment_date'    => 'nullable|date',
            'status'             => 'required',
        ]);
        $photo = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('students', 'public');
        }

        $student = Student::create([
            'student_code'       => $request->student_code,
            'department_id'      => $request->department_id,
            'class_id'           => $request->class_id,
            'first_name_khmer'   => $request->first_name_khmer,
            'last_name_khmer'    => $request->last_name_khmer,
            'first_name_english' => $request->first_name_english,
            'last_name_english'  => $request->last_name_english,
            'gender'             => $request->gender,
            'date_of_birth'      => $request->date_of_birth,
            'phone'              => $request->phone,
            'email'              => $request->email,
            'address'            => $request->address,
            'photo'              => $photo,
            'enrollment_date'    => $request->enrollment_date,
            'status'             => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student registered successfully.',
            'data'    => $student
        ], 201);
    }

    /**
     * Display the specified student profile.
     */
    public function show(string $id)
    {
        $student = Student::with(['department', 'classes'])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student record not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $student
        ], 200);
    }

    /**
     * Update the specified student profile.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student record not found.'
            ], 404);
        }

        $request->validate([
            'student_code'       => 'required|unique:students,student_code,' . $student->id,
            'department_id'      => 'required|exists:departments,id',
            'class_id'           => 'required|exists:classes,id',
            'first_name_khmer'   => 'required',
            'last_name_khmer'    => 'required',
            'first_name_english' => 'required',
            'last_name_english'  => 'required',
            'gender'             => 'required|in:Male,Female',
            'date_of_birth'      => 'nullable|date',
            'phone'              => 'nullable',
            'email'              => 'nullable|email|unique:students,email,' . $student->id,
            'address'            => 'nullable',
            'photo'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'enrollment_date'    => 'nullable|date',
            'status'             => 'required',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo asset if it exists to preserve storage space
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $student->photo = $request->file('photo')->store('students', 'public');
        }

        $student->update($request->except('photo'));

        return response()->json([
            'success' => true,
            'message' => 'Student profile updated successfully.',
            'data'    => $student->load(['department', 'classes'])
        ], 200);
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student record not found.'
            ], 404);
        }

        // Delete photo asset file from disk space
        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student registry profile removed successfully.'
        ], 200);
    }
}