<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class AssignmentSubmissionController extends Controller
{
    /**
     * GET ALL SUBMISSIONS
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = AssignmentSubmission::with(['assignment', 'student']);
        $query->where('student_id', $user->username);

        return response()->json([
            'success' => true,
            'data' => $query->latest()->get()
        ]);
    }

    /**
     * SHOW SINGLE SUBMISSION
     */
    public function show($id)
    {
        $submission = AssignmentSubmission::with(['assignment', 'student'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $submission
        ]);
    }

    /**
     * STUDENT SUBMIT ASSIGNMENT
     */
    public function store(Request $request)
    {
        $user = $request->user();

    $student = Student::where(
        'user_id',
        $user->id
    )->first();

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'Student not found'
        ], 404);
    }

    $request->validate([
        'assignment_id' => 'required|exists:assignments,id',
        'file' => 'required|file|max:10240',
    ]);

    $exists = AssignmentSubmission::where(
            'assignment_id',
            $request->assignment_id
        )
        ->where('student_id', $student->id)
        ->first();

    if ($exists) {
        return response()->json([
            'success' => false,
            'message' => 'Already submitted'
        ], 422);
    }

    $filePath = $request->file('file')
        ->store('submissions', 'public');

    $submission = AssignmentSubmission::create([
        'submission_code' => 'SUB-' . time(),
        'assignment_id' => $request->assignment_id,
        'student_id' => $student->id,
        'file_path' => $filePath,
        'submitted_at' => now(),
        'status' => 'Submitted',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Submitted successfully',
        'data' => $submission
    ]);
    }
}