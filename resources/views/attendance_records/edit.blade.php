@extends('layouts.myapp')

@section('title','Edit Attendance')

@section('content')

<div class="container-fluid">

<div class="card shadow border-0">

<div class="card-header bg-warning">
    <h4>Edit Attendance</h4>
</div>

<div class="card-body">

<form action="{{ route('attendance_records.update',$attendance->id) }}" method="POST">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-select">

        <option value="present" {{ $attendance->status=='present'?'selected':'' }}>Present</option>
        <option value="late" {{ $attendance->status=='late'?'selected':'' }}>Late</option>
        <option value="absent" {{ $attendance->status=='absent'?'selected':'' }}>Absent</option>

    </select>
</div>

<div class="mb-3">
    <label>Remark</label>
    <textarea name="remark" class="form-control">{{ $attendance->remark }}</textarea>
</div>

<div class="mb-3">
    <label>Check Out</label>
    <input type="datetime-local"
           name="check_out"
           class="form-control"
           value="{{ $attendance->check_out }}">
</div>

<button class="btn btn-primary">
    Update
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