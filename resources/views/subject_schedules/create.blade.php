@extends('layouts.myapp')

@section('title','Create Schedule')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-body">

<form action="{{ route('subject-schedules.store') }}" method="POST">
@csrf

<div class="row">

    <div class="col-md-4">
        <label>Course</label>
        <select name="course_id" class="form-control">
            @foreach($courses as $c)
                <option value="{{ $c->id }}">{{ $c->course_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Class</label>
        <select name="class_id" class="form-control">
            @foreach($classes as $c)
                <option value="{{ $c->id }}">{{ $c->class_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Semester</label>
        <select name="semester_id" class="form-control">
            @foreach($semesters as $s)
                <option value="{{ $s->id }}">{{ $s->semester_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mt-3">
        <label>Teacher</label>
        <select name="teacher_id" class="form-control">
            <option value="">-- None --</option>
            @foreach($teachers as $t)
                <option value="{{ $t->id }}">{{ $t->full_name_english }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mt-3">
        <label>Day</label>
        <select name="day_of_week" class="form-control">
            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                <option value="{{ $day }}">{{ $day }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 mt-3">
        <label>Start</label>
        <input type="time" name="start_time" class="form-control">
    </div>

    <div class="col-md-2 mt-3">
        <label>End</label>
        <input type="time" name="end_time" class="form-control">
    </div>

    <div class="col-md-4 mt-3">
        <label>Room</label>
        <input type="text" name="room" class="form-control">
    </div>

</div>

<br>

<button class="btn btn-primary">Save</button>
<a href="{{ route('subject-schedules.index') }}" class="btn btn-secondary">
    Cancel
</a>
</form>

</div>

</div>

</div>

@endsection