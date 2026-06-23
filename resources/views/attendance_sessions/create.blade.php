@extends('layouts.myapp')
@section('title','Create Attendance Session')
@section('content')
<div class="container-fluid">
<div class="card shadow-lg border-0">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">
            <i class="bi bi-plus-circle"></i>
            Create Attendance Session
        </h4>

    </div>

    <div class="card-body">

        <form action="{{ route('attendance_sessions.store') }}"
              method="POST">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Class
                    </label>

                    <select name="class_id"
                            class="form-select"
                            required>

                        <option value="">
                            Select Class
                        </option>

                        @foreach($classes as $class)

                        <option value="{{ $class->id }}">

                            {{ $class->class_name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Course
                    </label>

                    <select name="course_id"
                            class="form-select"
                            required>

                        <option value="">
                            Select Course
                        </option>

                        @foreach($courses as $course)

                        <option value="{{ $course->id }}">

                            {{ $course->course_name }}

                        </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Teacher
                    </label>

                    <select name="teacher_id"
                            class="form-select">

                        <option value="">
                            Select Teacher
                        </option>

                        @foreach($teachers as $teacher)

                        <option value="{{ $teacher->id }}">

                            {{ $teacher->first_name_english }}
                            {{ $teacher->last_name_english }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Session Date
                    </label>

                    <input type="date"
                           name="session_date"
                           class="form-control"
                           required>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Start Time
                    </label>

                    <input type="time"
                           name="start_time"
                           class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        End Time
                    </label>

                    <input type="time"
                           name="end_time"
                           class="form-control">

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Remark
                </label>

                <textarea name="remark"
                          rows="4"
                          class="form-control"></textarea>

            </div>

            <div class="text-end">

                <a href="{{ route('attendance_sessions.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-save"></i>
                    Save Session

                </button>

            </div>

        </form>

    </div>

</div>

</div>

@endsection
