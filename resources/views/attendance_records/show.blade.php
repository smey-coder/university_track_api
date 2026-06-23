@extends('layouts.myapp')
@section('title','Attendance Detail')
@section('content')
<div class="container-fluid">
    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
            <h4>Attendance Detail</h4>
        </div>
        <div class="card-body">
            <p><b>Student:</b> {{ $attendance->student->first_name_english ?? '-' }}  </p>
            <p><b>Session:</b> {{ $attendance->session->session_code ?? '-' }}</p>
            <p><b>Status:</b> {{ $attendance->status }}</p>
            <p><b>Check In:</b> {{ $attendance->check_in }}</p>
            <p><b>Check Out:</b> {{ $attendance->check_out }}</p>
            <p><b>Remark:</b> {{ $attendance->remark }}</p>
        </div>
    </div>
    <a href="{{ route('attendance_records.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>
</div>
@endsection