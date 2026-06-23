@extends('layouts.myapp')

@section('title','Create Attendance')

@section('content')

<div class="container-fluid">

<div class="card shadow border-0">

<div class="card-header bg-primary text-white">
    <h4>Create Attendance</h4>
</div>

<div class="card-body">

<form action="{{ route('attendance_records.store') }}" method="POST">
@csrf

{{-- SESSION --}}
<div class="mb-3">
    <label>Session</label>
    <select name="session_id" class="form-select" required>
        @foreach($sessions as $s)
            <option value="{{ $s->id }}">
                {{ $s->session_code }}
            </option>
        @endforeach
    </select>
</div>

{{-- STUDENT --}}
<div class="mb-3">
    <label>Student</label>
    <select name="student_id" class="form-select" required>
        @foreach($students as $st)
            <option value="{{ $st->id }}">
                {{ $st->student_code }} - {{ $st->first_name_english }} {{ $st->last_name_english }}
            </option>
        @endforeach
    </select>
</div>

{{-- STATUS --}}
<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-select">
        <option value="present">Present</option>
        <option value="late">Late</option>
        <option value="absent">Absent</option>
    </select>
</div>

{{-- REMARK --}}
<div class="mb-3">
    <label>Remark</label>
    <textarea name="remark" class="form-control"></textarea>
</div>

<button class="btn btn-success">
    Save Attendance
</button>
<a href="{{ route('attendance_records.index') }}"
           class="btn btn-secondary">
            Cancel
</a>
</form>

</div>

</div>

</div>

@endsection