@extends('layouts.myapp')

@section('title','Edit Attendance Session')

@section('content')

<div class="container-fluid">

<div class="card shadow-lg border-0">

    <div class="card-header bg-warning text-dark">

        <h4 class="mb-0">
            <i class="bi bi-pencil-square"></i>
            Edit Attendance Session
        </h4>

    </div>

    <div class="card-body">

        <form action="{{ route('attendance_sessions.update', $session->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Class</label>

                    <select name="class_id" class="form-select" required>

                        @foreach($classes as $class)

                            <option value="{{ $class->id }}"
                                {{ $session->class_id == $class->id ? 'selected' : '' }}>

                                {{ $class->class_name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Course</label>

                    <select name="course_id" class="form-select" required>

                        @foreach($courses as $course)

                            <option value="{{ $course->id }}"
                                {{ $session->course_id == $course->id ? 'selected' : '' }}>

                                {{ $course->course_name }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Teacher</label>

                    <select name="teacher_id" class="form-select">

                        @foreach($teachers as $teacher)

                            <option value="{{ $teacher->id }}"
                                {{ $session->teacher_id == $teacher->id ? 'selected' : '' }}>

                                {{ $teacher->first_name_english }} {{ $teacher->last_name_english }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Session Date</label>

                    <input type="date"
                           name="session_date"
                           class="form-control"
                           value="{{ $session->session_date }}"
                           required>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Start Time</label>

                    <input type="time"
                           name="start_time"
                           class="form-control"
                           value="{{ $session->start_time }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">End Time</label>

                    <input type="time"
                           name="end_time"
                           class="form-control"
                           value="{{ $session->end_time }}">

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Remark</label>

                <textarea name="remark"
                          class="form-control"
                          rows="4">{{ $session->remark }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">Status</label>

                <select name="status" class="form-select">

                    <option value="active" {{ $session->status=='active'?'selected':'' }}>
                        Active
                    </option>

                    <option value="finished" {{ $session->status=='finished'?'selected':'' }}>
                        Finished
                    </option>

                </select>

            </div>

            <div class="text-end">

                <a href="{{ route('attendance_sessions.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

                <button class="btn btn-warning">

                    Update Session

                </button>

            </div>

        </form>

    </div>

</div>
</div>

@endsection
