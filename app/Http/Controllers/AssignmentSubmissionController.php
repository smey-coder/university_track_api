<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignmentSubmission;
use App\Models\Assignment;
use App\Models\Student;
use Carbon\Carbon;

class AssignmentSubmissionController extends Controller
{
    public function index()
    {
        $submissions = AssignmentSubmission::with('assignment','student')
            ->latest()
            ->get();

        return view('assignment_submissions.index', compact('submissions'));
    }

    public function create()
    {
        $assignments = Assignment::all();
        $students = Student::all();

        return view('assignment_submissions.create', compact('assignments','students'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // auto submitted time
        $data['submitted_at'] = Carbon::now();

        AssignmentSubmission::create($data);

        return redirect()->route('assignment_submissions.index')
            ->with('success','Submission created successfully');
    }

    public function show($id)
    {
        $submission = AssignmentSubmission::with('assignment','student')
            ->findOrFail($id);

        return view('assignment_submissions.show', compact('submission'));
    }

    public function edit($id)
    {
        $submission = AssignmentSubmission::findOrFail($id);
        $assignments = Assignment::all();
        $students = Student::all();

        return view('assignment_submissions.edit', compact('submission','assignments','students'));
    }

    public function update(Request $request, $id)
    {
        $submission = AssignmentSubmission::findOrFail($id);
        $submission->update($request->all());

        return redirect()->route('assignment_submissions.index')
            ->with('success','Submission updated successfully');
    }

    // 🎯 GRADING FUNCTION (IMPORTANT)
    public function grade(Request $request, $id)
    {
        $submission = AssignmentSubmission::findOrFail($id);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'status' => 'Graded'
        ]);

        return back()->with('success','Submission graded successfully');
    }

    public function destroy($id)
    {
        AssignmentSubmission::findOrFail($id)->delete();

        return back()->with('success','Submission deleted');
    }
}