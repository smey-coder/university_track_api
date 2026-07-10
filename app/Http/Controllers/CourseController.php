<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Department;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('department','teacher')->latest()->get();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $departments = Department::all();
        $teachers = Teacher::all();

        return view('courses.create', compact('departments','teachers'));
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
            'course_code' => 'required|unique:courses',
            'department_id' => 'required',
            'course_name' => 'required|unique:courses',
            'credits' => 'required|integer',
        ]);

        Course::create($request->all());

        return redirect()->route('courses.index')
            ->with('success','Course created successfully');
        }catch(\Exception $e){
            return back()->with(
                'error',
                'Course manager not found or failed to create. ' . $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        $course = Course::with('department','teacher')->findOrFail($id);
        return view('courses.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $departments = Department::all();
        $teachers = Teacher::all();

        return view('courses.edit', compact('course','departments','teachers'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->all());

        return redirect()->route('courses.index')
            ->with('success','Course updated successfully');
    }

    public function destroy($id)
    {
        Course::destroy($id);

        return redirect()->route('courses.index')
            ->with('success','Course deleted successfully');
    }
}
