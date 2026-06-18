@extends('layouts.myapp')

@section('title','Grade Submission')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-warning">
    <h4>Teacher Grading Panel</h4>
</div>

<div class="card-body">

<form method="POST"
      action="{{ route('assignment_submissions.update',$submission->id) }}">

@csrf
@method('PUT')

<div class="mb-3">
    <label>Score</label>
    <input type="number"
           name="score"
           value="{{ $submission->score }}"
           class="form-control">
</div>

<div class="mb-3">
    <label>Feedback</label>
    <textarea name="feedback"
              class="form-control">{{ $submission->feedback }}</textarea>
</div>

<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control">
        <option {{ $submission->status=='Submitted'?'selected':'' }}>Submitted</option>
        <option {{ $submission->status=='Late'?'selected':'' }}>Late</option>
        <option {{ $submission->status=='Graded'?'selected':'' }}>Graded</option>
    </select>
</div>

<button class="btn btn-success">
    Save Grade
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