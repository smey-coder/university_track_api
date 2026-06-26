<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Semester;
use App\Models\StudentClass;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectScheduleController extends Controller
{
    /**
     * Display a listing of schedules.
     */
    public function index()
    {
        try {
            $schedules = SubjectSchedule::with([
                'course',
                'class',
                'semester',
                'teacher'
            ])
            ->latest()
            ->paginate(10);

            return view('subject_schedules.index', compact('schedules'));

        } catch (\Exception $e) {

            Log::error('Subject Schedule Index Error: ' . $e->getMessage());

            return redirect()->back()->with(
                'error',
                'Unable to load schedules.'
            );
        }
    }

    /**
     * Show create form.
     */
    public function create()
    {
        try {

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

        } catch (\Exception $e) {

            Log::error('Create Schedule Error: ' . $e->getMessage());

            return redirect()->back()->with(
                'error',
                'Unable to open create page.'
            );
        }
    }

    /**
     * Store a new schedule.
     */
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'course_id'     => 'required|exists:courses,id',
                'class_id'      => 'required|exists:classes,id',
                'semester_id'   => 'required|exists:semesters,id',
                'teacher_id'    => 'nullable|exists:teachers,id',
                'day_of_week'   => 'required|string',
                'start_time'    => 'required',
                'end_time'      => 'required',
                'room'          => 'nullable|string|max:100',
                'created_at'    => 'nullable|date',
                'updated_at'      => 'nullable|date|after_or_equal:created_at',
                'status'        => 'required|in:active,finished',
            ]);

            SubjectSchedule::create($validated);

            return redirect()
                ->route('subject-schedules.index')
                ->with('success', 'Schedule created successfully.');

        } catch (\Exception $e) {

            Log::error('Store Schedule Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create schedule.');
        }
    }

    /**
     * Show schedule details.
     */
    public function show($id)
    {
        try {

            $schedule = SubjectSchedule::with([
                'course',
                'class',
                'semester',
                'teacher'
            ])->findOrFail($id);

            return view('subject_schedules.show', compact('schedule'));

        } catch (\Exception $e) {

            Log::error('Show Schedule Error: ' . $e->getMessage());

            return redirect()
                ->route('subject-schedules.index')
                ->with('error', 'Schedule not found.');
        }
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        try {

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

        } catch (\Exception $e) {

            Log::error('Edit Schedule Error: ' . $e->getMessage());

            return redirect()
                ->route('subject-schedules.index')
                ->with('error', 'Unable to edit schedule.');
        }
    }

    /**
     * Update schedule.
     */
    public function update(Request $request, $id)
    {
        try {

            $validated = $request->validate([
                'course_id'     => 'required|exists:courses,id',
                'class_id'      => 'required|exists:classes,id',
                'semester_id'   => 'required|exists:semesters,id',
                'teacher_id'    => 'nullable|exists:teachers,id',
                'day_of_week'   => 'required|string',
                'start_time'    => 'required',
                'end_time'      => 'required',
                'room'          => 'nullable|string|max:100',
                'created_at'    => 'nullable|date',
                'updated_at'      => 'nullable|date|after_or_equal:created_at',
                'status'        => 'required|in:active,finished',
            ]);

            $schedule = SubjectSchedule::findOrFail($id);

            $schedule->update($validated);

            return redirect()
                ->route('subject-schedules.index')
                ->with('success', 'Schedule updated successfully.');

        } catch (\Exception $e) {

            Log::error('Update Schedule Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update schedule.');
        }
    }

    /**
     * Delete schedule.
     */
    public function destroy($id)
    {
        try {

            $schedule = SubjectSchedule::findOrFail($id);

            $schedule->delete();

            return redirect()
                ->route('subject-schedules.index')
                ->with('success', 'Schedule deleted successfully.');

        } catch (\Exception $e) {

            Log::error('Delete Schedule Error: ' . $e->getMessage());

            return redirect()
                ->route('subject-schedules.index')
                ->with('error', 'Failed to delete schedule.');
        }
    }
}