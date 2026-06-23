<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\StudentClass;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceSessionController extends Controller
{
    /**
     * Display all attendance sessions
     */
    public function index()
    {
        $sessions = AttendanceSession::with([
            'class',
            'course',
            'teacher'
        ])
        ->latest()
        ->paginate(15);

        return view(
            'attendance_sessions.index',
            compact('sessions')
        );
    }

    /**
     * Show create form
     */
    public function create()
    {
        $classes = StudentClass::all();
        $courses = Course::all();
        $teachers = Teacher::all();
        return view('attendance_sessions.create',compact('classes','courses','teachers'));
    }

    /**
     * Generate Session Code
     */
    private function generateSessionCode()
    {
        $lastSession = AttendanceSession::latest('id')->first();

        $number = $lastSession
            ? $lastSession->id + 1
            : 1;

        return 'SES-' .date('Ymd') .str_pad($number,4,'0',STR_PAD_LEFT);
    }

    /**
     * Generate Attendance Code
     */
    private function generateAttendanceCode()
    {
        return 'ATT-' . rand(100000, 999999);
    }

    /**
     * Store new session
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'course_id' => 'required|exists:courses,id',
            'session_date' => 'required|date',
        ]);

        AttendanceSession::create([
            'session_code' => $this->generateSessionCode(),
            'attendance_code' => $this->generateAttendanceCode(),
            'class_id' => $request->class_id,
            'course_id' => $request->course_id,
            'teacher_id' => $request->teacher_id,
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'remark' => $request->remark,
            'status' => 'active',
            'qr_code' => (string) Str::uuid(),
        ]);

        return redirect()
            ->route('attendance_sessions.index')
            ->with(
                'success',
                'Attendance Session Created Successfully'
            );
    }

    /**
     * Show session details
     */
    public function show($id)
    {
        $session = AttendanceSession::with([
            'class',
            'course',
            'teacher',
            'attendances.student'
        ])->findOrFail($id);

        return view(
            'attendance_sessions.show',
            compact('session')
        );
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $session = AttendanceSession::findOrFail($id);

        $classes = StudentClass::all();
        $courses = Course::all();
        $teachers = Teacher::all();

        return view(
            'attendance_sessions.edit',
            compact(
                'session',
                'classes',
                'courses',
                'teachers'
            )
        );
    }

    /**
     * Update session
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'course_id' => 'required|exists:courses,id',
            'session_date' => 'required|date',
        ]);

        $session = AttendanceSession::findOrFail($id);

        $session->update([
            'class_id' => $request->class_id,
            'course_id' => $request->course_id,
            'teacher_id' => $request->teacher_id,
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'remark' => $request->remark,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('attendance_sessions.index')
            ->with(
                'success',
                'Attendance Session Updated Successfully'
            );
    }

    /**
     * Close session
     */
    public function close($id)
    {
        $session = AttendanceSession::findOrFail($id);

        $session->update([
            'status' => 'finished'
        ]);

        return back()->with(
            'success',
            'Attendance Session Closed Successfully'
        );
    }

    /**
     * Re-generate Attendance Code
     */
    public function regenerateCode($id)
    {
        $session = AttendanceSession::findOrFail($id);

        $session->update([
            'attendance_code' => $this->generateAttendanceCode(),
            'qr_code' => (string) Str::uuid(),
        ]);

        return back()->with(
            'success',
            'New Attendance Code Generated Successfully'
        );
    }

    /**
     * Delete session
     */
    public function destroy($id)
    {
        $session = AttendanceSession::findOrFail($id);

        $session->delete();

        return redirect()
            ->route('attendance_sessions.index')
            ->with(
                'success',
                'Attendance Session Deleted Successfully'
            );
    }
}