@extends('layouts.myapp')

@section('title', 'Edit Subject Schedule')

@section('content')

<div class="container py-4">

    <div class="card shadow border-0">

        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">
                <i class="bi bi-pencil-square"></i>
                Edit Subject Schedule
            </h4>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            <form action="{{ route('subject-schedules.update', $schedule->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Course --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Course
                        </label>

                        <select name="course_id" class="form-select" required>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}"
                                    {{ old('course_id', $schedule->course_id) == $course->id ? 'selected' : '' }}>

                                    {{ $course->course_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Class --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Class
                        </label>

                        <select name="class_id" class="form-select" required>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $schedule->class_id) == $class->id ? 'selected' : '' }}>

                                    {{ $class->class_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Semester --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Semester
                        </label>

                        <select name="semester_id" class="form-select" required>

                            @foreach($semesters as $semester)

                                <option value="{{ $semester->id }}"
                                    {{ old('semester_id', $schedule->semester_id) == $semester->id ? 'selected' : '' }}>

                                    {{ $semester->semester_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Teacher --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Teacher
                        </label>

                        <select name="teacher_id" class="form-select">

                            <option value="">-- None --</option>

                            @foreach($teachers as $teacher)

                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>

                                    {{ $teacher->full_name_english }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Day --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Day of Week
                        </label>

                        <select name="day_of_week" class="form-select">

                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)

                                <option value="{{ $day }}"
                                    {{ old('day_of_week', $schedule->day_of_week) == $day ? 'selected' : '' }}>

                                    {{ $day }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Room --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Room
                        </label>

                        <input
                            type="text"
                            name="room"
                            class="form-control"
                            value="{{ old('room', $schedule->room) }}">

                    </div>

                    {{-- Start Time --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Start Time
                        </label>

                        <input
                            type="time"
                            name="start_time"
                            class="form-control"
                            value="{{ old('start_time', $schedule->start_time) }}"
                            required>

                    </div>

                    {{-- End Time --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            End Time
                        </label>

                        <input
                            type="time"
                            name="end_time"
                            class="form-control"
                            value="{{ old('end_time', $schedule->end_time) }}"
                            required>

                    </div>

                    {{-- Start Date --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ old('created_date', optional($schedule->created_at)->format('Y-m-d')) }}">

                    </div>

                    {{-- End Date --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ old('updated_at', optional($schedule->updated_at)->format('Y-m-d')) }}">

                    </div>

                    {{-- Max Students --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Maximum Students
                        </label>

                        <input
                            type="number"
                            name="max_students"
                            class="form-control"
                            value="{{ old('max_students', $schedule->max_students) }}"
                            min="1">

                    </div>

                    {{-- Status --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Status
                        </label>

                        <select name="status" class="form-select" required>

                            <option value="active"
                                {{ old('status', $schedule->status) == 'active' ? 'selected' : '' }}>
                                🟢 Active
                            </option>

                            <option value="finished"
                                {{ old('status', $schedule->status) == 'finished' ? 'selected' : '' }}>
                                🔴 Finished
                            </option>

                        </select>

                    </div>

                </div>

                <hr>

                <button class="btn btn-warning">
                    <i class="bi bi-save"></i>
                    Update Schedule
                </button>

                <a href="{{ route('subject-schedules.index') }}"
                   class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection