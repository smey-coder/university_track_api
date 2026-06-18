@extends('layouts.myapp')

@section('title','Submission Detail')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-info text-white">
    <h4>Submission Detail</h4>
</div>

<div class="card-body">

    <p><b>Code:</b> {{ $submission->submission_code }}</p>

    <p><b>Student:</b>
        {{ $submission->student->first_name_english ?? '-' }}
    </p>

    <p><b>Assignment:</b>
        {{ $submission->assignment->title ?? '-' }}
    </p>

    <p><b>Submitted At:</b> {{ $submission->submitted_at }}</p>

    <p><b>Status:</b> {{ $submission->status }}</p>

    <p><b>Score:</b> {{ $submission->score ?? 'Not graded' }}</p>

    <p><b>Feedback:</b> {{ $submission->feedback ?? '-' }}</p>

    @if($submission->file_path)
        <a href="{{ asset('storage/'.$submission->file_path) }}"
           class="btn btn-primary">
            Download File
        </a>
    @endif

    <a href="{{ route('assignment_submissions.index') }}"
       class="btn btn-secondary">
        Back
    </a>

</div>

</div>

</div>

@endsection