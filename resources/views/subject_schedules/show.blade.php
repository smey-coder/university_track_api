@extends('layouts.myapp')

@section('title','Schedule Detail')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-body">

<h4>Schedule Detail</h4>

<hr>

<p><b>Course:</b> {{ $schedule->course->name }}</p>
<p><b>Class:</b> {{ $schedule->class->class_name }}</p>
<p><b>Semester:</b> {{ $schedule->semester->semester_name }}</p>
<p><b>Teacher:</b> {{ $schedule->teacher->full_name_english ?? '-' }}</p>
<p><b>Day:</b> {{ $schedule->day_of_week }}</p>
<p><b>Time:</b> {{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
<p><b>Room:</b> {{ $schedule->room }}</p>

<a href="{{ route('subject-schedules.index') }}"
   class="btn btn-secondary">
   Back
</a>

</div>

</div>

</div>

@endsection