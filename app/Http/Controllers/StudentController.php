<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\StudentClass;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index()
    {
        $students = Student::with('department', 'classes')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $departments = Department::orderBy('department_name_english')->get();
        $class = StudentClass::orderBy('class_name')->get();
        return view('students.create', compact('departments', 'class'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_code' => 'required|unique:students,student_code',
            'department_id' => 'required|exists:departments,id',
            'class_id' => 'required|exists:classes,id',
            'first_name_khmer' => 'required',
            'last_name_khmer' => 'required',
            'first_name_english' => 'required',
            'last_name_english' => 'required',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable',
            'email' => 'nullable|email|unique:students,email',
            'address' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'enrollment_date' => 'nullable|date',
            'status' => 'required',
        ]);

        $photo = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('students', 'public');
        }

        Student::create([
            'student_code' => $request->student_code,
            'department_id' => $request->department_id,
            'class_id' => $request->class_id,
            'first_name_khmer' => $request->first_name_khmer,
            'last_name_khmer' => $request->last_name_khmer,
            'first_name_english' => $request->first_name_english,
            'last_name_english' => $request->last_name_english,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'photo' => $photo,
            'enrollment_date' => $request->enrollment_date,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student added successfully.');
    }
    public function show(string $id)
    {
        $students = Student::findOrFail($id);
        return view('students.show', compact('students'));
    }

    /**
     * Show the edit form.
     */
    public function edit(Student $student)
    {
        $departments = Department::orderBy('department_name_english')->get();
        $class = StudentClass::orderBy('class_name')->get();

        return view('students.edit', compact('student', 'departments', 'class'));
    }

    /**
     * Update student.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_code' => 'required|unique:students,student_code,' . $student->id,
            'department_id' => 'required|exists:departments,id',
            'class_id' => 'required|exists:classes,id',
            'first_name_khmer' => 'required',
            'last_name_khmer' => 'required',

            'first_name_english' => 'required',
            'last_name_english' => 'required',

            'gender' => 'required|in:Male,Female',

            'date_of_birth' => 'nullable|date',

            'phone' => 'nullable',

            'email' => 'nullable|email|unique:students,email,' . $student->id,

            'address' => 'nullable',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'enrollment_date' => 'nullable|date',

            'status' => 'required',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')
                ->store('students', 'public');

            $student->photo = $photo;
        }

        $student->student_code = $request->student_code;
        $student->department_id = $request->department_id;
        $student->class_id = $request->class_id;
        $student->first_name_khmer = $request->first_name_khmer;
        $student->last_name_khmer = $request->last_name_khmer;

        $student->first_name_english = $request->first_name_english;
        $student->last_name_english = $request->last_name_english;

        $student->gender = $request->gender;
        $student->date_of_birth = $request->date_of_birth;

        $student->phone = $request->phone;
        $student->email = $request->email;

        $student->address = $request->address;

        $student->enrollment_date = $request->enrollment_date;

        $student->status = $request->status;

        $student->save();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Delete student.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
