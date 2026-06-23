<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
class AttendanceRecordController extends Controller
{
    // 📊 1. VIEW ATTENDANCE BY SUBJECT
    public function index()
    {
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        $attendances = Attendance::with(['session.course'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // group by subject
        $data = $attendances->groupBy(function ($item) {
            return $item->session->course->course_name ?? 'Unknown';
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // 📷 2. SCAN ATTENDANCE (QR OR CODE INPUT)
    public function scan(Request $request)
    {
        $request->validate([
            'attendance_code' => 'required|string'
        ]);

        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        // 🔵 Find session by code
        $session = AttendanceSession::where('attendance_code', $request->attendance_code)
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired code'
            ], 400);
        }

        // 🔴 Prevent duplicate
        $exists = Attendance::where('session_id', $session->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already marked attendance'
            ], 409);
        }

        // 🟢 Create attendance
        $attendance = Attendance::create([
            'attendance_code' => $this->generateAttendanceCode(),
            'session_id' => $session->id,
            'student_id' => $student->id,
            'status' => 'Present',
            'check_in' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance marked successfully',
            'data' => $attendance
        ]);
    }
    public function summary()
    {
        $student = Student::where(
            'user_id',
            Auth::id()
        )->first();

        return response()->json([
            'present' => Attendance::where(
                'student_id',
                $student->id
            )->where('status','Present')->count(),

            'late' => Attendance::where(
                'student_id',
                $student->id
            )->where('status','Late')->count(),

            'absent' => Attendance::where(
                'student_id',
                $student->id
            )->where('status','Absent')->count(),
        ]);
    }
    public function subjectAttendance()
    {
        $student = Student::where(
            'user_id',
            Auth::id()
        )->first();

        $data = Attendance::with([
            'session.course'
        ])
        ->where('student_id', $student->id)
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

     private function generateAttendanceCode()
    {
        return 'ATD-' . Str::upper(Str::uuid());
    }
}
