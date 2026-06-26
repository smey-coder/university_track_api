<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with([
            'student.classes',
            'session.course'
        ]);

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('attendance_code', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhereHas('student', function ($student) use ($search) {

                        $student->where('student_code', 'like', "%{$search}%")
                                ->orWhere('first_name_english', 'like', "%{$search}%")
                                ->orWhere('last_name_english', 'like', "%{$search}%");

                })
                ->orWhereHas('session.course', function ($course) use ($search) {

                        $course->where('course_name', 'like', "%{$search}%");

                })
                ->orWhereHas('student.classes', function ($class) use ($search) {

                        $class->where('class_name', 'like', "%{$search}%");

                });

            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'attendance_records.index',
            compact('attendances')
        );
    }

    public function create()
    {
        $students = Student::all();

        $sessions = AttendanceSession::where(
            'status',
            'active'
        )->get();

        return view('attendance_records.create',compact('students','sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'student_id' => 'required',
            'status' => 'required',
        ]);

        Attendance::create([
            'attendance_code' =>'ATD-' . rand(100000,999999),
            'session_id' =>$request->session_id,
            'student_id' =>$request->student_id,
            'status' =>$request->status ?? 'Present',
            'remark' =>$request->remark,
            'check_in' =>now(),
        ]);

        return redirect()
            ->route('attendance_records.index')
            ->with(
                'success',
                'Attendance Created Successfully'
            );
    }

    public function show($id)
    {
        $attendance = Attendance::with([
            'student',
            'session'
        ])->findOrFail($id);

        return view(
            'attendance_records.show',
            compact('attendance')
        );
    }

    public function edit($id)
    {
        $attendance =
            Attendance::findOrFail($id);

        $students = Student::all();

        $sessions = AttendanceSession::all();

        return view(
            'attendance_records.edit',
            compact(
                'attendance',
                'students',
                'sessions'
            )
        );
    }

    public function update(
        Request $request,
        $id
    ) {
        $attendance =
            Attendance::findOrFail($id);

        $attendance->update([
            'status' =>
                $request->status,

            'remark' =>
                $request->remark,

            'check_out' =>
                $request->check_out,
        ]);

        return redirect()
            ->route('attendance_records.index')
            ->with(
                'success',
                'Attendance Updated Successfully'
            );
    }

    public function destroy($id)
    {
        Attendance::findOrFail($id)
            ->delete();

        return back()->with(
            'success',
            'Attendance Deleted Successfully'
        );
    }
    public function processScan(Request $request)
    {
        $request->validate([
            'attendance_code' => 'required|string'
        ]);
        //  1. Find session by attendance_code
        $session = AttendanceSession::where('attendance_code', $request->attendance_code)
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return back()->with('error', 'Invalid or expired attendance code');
        }

        //  2. Get student
        $student = null;

        if ($request->filled('student_id')) {
            $student = Student::find($request->student_id);
        } elseif (auth()->check()) {
            $student = Student::where('user_id', auth()->id())->first();
        }

        if (!$student) {
            return back()->with('error', 'Student not found');
        }

        //  3. Prevent duplicate attendance
        $exists = Attendance::where('session_id', $session->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already checked in');
        }

        //  4. Save attendance
        Attendance::create([
            'attendance_code' => $this->generateAttendanceCode(),
            'session_id' => $session->id,
            'student_id' => $student->id,
            'status' => 'Present',
            'remark' => null,
            'check_in' => now(),
        ]);

        return redirect()
            ->route('attendance_records.index')
            ->with('success', 'Attendance marked successfully');
    }
    //Qrcode
    private function generateAttendanceCode()
    {
        return 'ATD-' . Str::upper(Str::uuid());
    }

    public function scanForm()
    {
        $students = Student::all();
        return view('attendance_records.scan', compact('students'));
    }

    public function scanQRForm(Request $request)
    {
        return view('attendance_records.scan', [
            'session_code' => $request->session_code
        ]);
    }
    public function processQRScan(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string'
        ]);

        // 🔵 Find session
        $session = AttendanceSession::where('session_code', $request->session_code)
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return back()->with('error', 'Invalid QR Code');
        }

        // 🟡 Student (login user)
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return back()->with('error', 'Student not found');
        }

        // 🔴 Prevent duplicate
        $exists = Attendance::where('session_id', $session->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Already marked attendance');
        }

        // 🟢 Create attendance
        Attendance::create([
            'attendance_code' => $session->session_code,
            'session_id' => $session->id,
            'student_id' => $student->id,
            'status' => 'present',
            'check_in' => now(),
        ]);

        return redirect()
            ->route('attendance_records.index')
            ->with('success', 'Attendance marked via QR successfully');
    }
    public function scanQRPage()
    {
        $session = AttendanceSession::where('status', 'active')
            ->latest('id')
            ->first();

        if (!$session) {
            return redirect()
                ->route('attendance_records.index')
                ->with('error', 'No active attendance session found for QR scan.');
        }

        return view('attendance_records.scan_qrcode', compact('session'));
    }
}