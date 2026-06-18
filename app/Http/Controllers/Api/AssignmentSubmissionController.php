<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignmentSubmission;
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

        // If student → show only own submissions
        if ($user->role === 'student') {
            $query->where('student_id', $user->id);
        }

        $data = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * SHOW SINGLE
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
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file' => 'required|file|max:10240', // 10MB
        ]);

        $user = $request->user();

        // Upload file
        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        $submission = AssignmentSubmission::create([
            'submission_code' => 'SUB-' . time(),
            'assignment_id'   => $request->assignment_id,
            'student_id'      => $user->id,
            'file_path'       => $filePath,
            'submitted_at'    => now(),
            'status'          => 'Submitted',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Assignment submitted successfully',
            'data' => $submission
        ]);
    }
}
