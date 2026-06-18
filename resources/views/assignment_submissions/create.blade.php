@extends('layouts.myapp')

@section('title','Submit Assignment')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">
    <h4>Submit Assignment</h4>
</div>

<div class="card-body">

<form method="POST" action="{{ route('assignment_submissions.store') }}"
      enctype="multipart/form-data">

@csrf

<div class="mb-3">
    <label>Submission Code</label>
    <input name="submission_code" class="form-control">
</div>

<div class="mb-3">
    <label>Assignment</label>
    <select name="assignment_id" class="form-control">
        @foreach($assignments as $a)
            <option value="{{ $a->id }}">{{ $a->title }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Student</label>
    <select name="student_id" class="form-control">
        @foreach($students as $s)
            <option value="{{ $s->id }}">
                {{ $s->first_name_english }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Upload File</label>
    <input type="file" name="file_path" class="form-control">
</div>

<button class="btn btn-success">
    Submit
</button>

<a href="{{ route('assignment_submissions.index') }}"
   class="btn btn-secondary">
    Cancel
</a>

</form>

</div>

</div>

</div>

@endsection