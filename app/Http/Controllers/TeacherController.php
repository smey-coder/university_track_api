<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index()
    {
        $teachers = Teacher::with('department')
            ->latest()
            ->paginate(10);

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        $departments = Department::where('status', 'Active')->get();

        return view('teachers.create', compact('departments'));
    }

    /**
     * Store a newly created teacher.
     */
    public function store(Request $request)
    {
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
            'email'               => 'nullable|email|max:255',
            'address'             => 'nullable|string',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hire_date'           => 'nullable|date',
            'status'              => 'required|in:Active,Inactive',
        ]);

        $teacher = new Teacher();

        $teacher->teacher_code = $request->teacher_code;
        $teacher->department_id = $request->department_id;
        $teacher->first_name_khmer = $request->first_name_khmer;
        $teacher->last_name_khmer = $request->last_name_khmer;
        $teacher->first_name_english = $request->first_name_english;
        $teacher->last_name_english = $request->last_name_english;
        $teacher->gender = $request->gender;
        $teacher->date_of_birth = $request->date_of_birth;
        $teacher->phone = $request->phone;
        $teacher->email = $request->email;
        $teacher->address = $request->address;
        $teacher->hire_date = $request->hire_date;
        $teacher->status = $request->status;

        if ($request->hasFile('photo')) {
            $teacher->photo = $request->file('photo')
                ->store('teachers', 'public');
        }

        $teacher->save();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified teacher.
     */
    public function show(string $id)
    {
        $teacher = Teacher::with('department')->findOrFail($id);

        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);

        $departments = Department::where('status', 'Active')->get();

        return view('teachers.edit', compact('teacher', 'departments'));
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);

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
            'email'               => 'nullable|email|max:255',
            'address'             => 'nullable|string',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hire_date'           => 'nullable|date',
            'status'              => 'required|in:Active,Inactive',
        ]);

        $teacher->teacher_code = $request->teacher_code;
        $teacher->department_id = $request->department_id;
        $teacher->first_name_khmer = $request->first_name_khmer;
        $teacher->last_name_khmer = $request->last_name_khmer;
        $teacher->first_name_english = $request->first_name_english;
        $teacher->last_name_english = $request->last_name_english;
        $teacher->gender = $request->gender;
        $teacher->date_of_birth = $request->date_of_birth;
        $teacher->phone = $request->phone;
        $teacher->email = $request->email;
        $teacher->address = $request->address;
        $teacher->hire_date = $request->hire_date;
        $teacher->status = $request->status;

        if ($request->hasFile('photo')) {

            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $teacher->photo = $request->file('photo')
                ->store('teachers', 'public');
        }

        $teacher->save();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
