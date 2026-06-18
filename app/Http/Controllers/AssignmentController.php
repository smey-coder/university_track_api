<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with('course','teacher')->latest()->get();
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $courses = Course::all();
        $teachers = Teacher::all();

        return view('assignments.create', compact('courses','teachers'));
    }

    public function store(Request $request)
    {
        Assignment::create($request->all());

        return redirect()->route('assignments.index')
            ->with('success','Assignment created successfully');
    }

    public function show($id)
    {
        $assignment = Assignment::with('course','teacher')->findOrFail($id);

        return view('assignments.show', compact('assignment'));
    }

    public function edit($id)
    {
        $assignment = Assignment::findOrFail($id);
        $courses = Course::all();
        $teachers = Teacher::all();

        return view('assignments.edit', compact('assignment','courses','teachers'));
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->update($request->all());

        return redirect()->route('assignments.index')
            ->with('success','Assignment updated successfully');
    }

    public function destroy($id)
    {
        Assignment::findOrFail($id)->delete();

        return back()->with('success','Assignment deleted');
    }
}
