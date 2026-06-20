<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectSchedule;
use App\Models\Course;
use App\Models\StudentClass;
use App\Models\Semester;
use App\Models\Teacher;

class SubjectScheduleController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $schedules = SubjectSchedule::with([
            'course',
            'class',
            'semester',
            'teacher'
        ])->latest()->paginate(10);

        return view('subject_schedules.index', compact('schedules'));
    }

    // ================= CREATE =================
    public function create()
    {
        $courses = Course::all();
        $classes = StudentClass::all();
        $semesters = Semester::all();
        $teachers = Teacher::all();

        return view('subject_schedules.create', compact(
            'courses',
            'classes',
            'semesters',
            'teachers'
        ));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'class_id' => 'required',
            'semester_id' => 'required',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        SubjectSchedule::create($request->all());

        return redirect()->route('subject-schedules.index')
            ->with('success', 'Schedule created successfully');
    }

    // ================= SHOW =================
    public function show($id)
    {
        $schedule = SubjectSchedule::with([
            'course',
            'class',
            'semester',
            'teacher'
        ])->findOrFail($id);

        return view('subject_schedules.show', compact('schedule'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $schedule = SubjectSchedule::findOrFail($id);

        $courses = Course::all();
        $classes = StudentClass::all();
        $semesters = Semester::all();
        $teachers = Teacher::all();

        return view('subject_schedules.edit', compact(
            'schedule',
            'courses',
            'classes',
            'semesters',
            'teachers'
        ));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required',
            'class_id' => 'required',
            'semester_id' => 'required',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $schedule = SubjectSchedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('subject-schedules.index')
            ->with('success', 'Schedule updated successfully');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        SubjectSchedule::findOrFail($id)->delete();

        return redirect()->route('subject-schedules.index')
            ->with('success', 'Schedule deleted successfully');
    }
}