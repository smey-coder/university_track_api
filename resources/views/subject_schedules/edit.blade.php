@extends('layouts.myapp')

@section('title','Edit Schedule')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square"></i>
                Edit Subject Schedule
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('subject-schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- COURSE -->
                    <div class="col-md-4 mb-3">
                        <label>Course</label>
                        <select name="course_id" class="form-control" required>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}"
                                    {{ $schedule->course_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- CLASS -->
                    <div class="col-md-4 mb-3">
                        <label>Class</label>
                        <select name="class_id" class="form-control" required>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}"
                                    {{ $schedule->class_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->class_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SEMESTER -->
                    <div class="col-md-4 mb-3">
                        <label>Semester</label>
                        <select name="semester_id" class="form-control" required>
                            @foreach($semesters as $s)
                                <option value="{{ $s->id }}"
                                    {{ $schedule->semester_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- TEACHER -->
                    <div class="col-md-4 mb-3">
                        <label>Teacher</label>
                        <select name="teacher_id" class="form-control">
                            <option value="">-- None --</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}"
                                    {{ $schedule->teacher_id == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- DAY -->
                    <div class="col-md-4 mb-3">
                        <label>Day of Week</label>
                        <select name="day_of_week" class="form-control" required>
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <option value="{{ $day }}"
                                    {{ $schedule->day_of_week == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- START TIME -->
                    <div class="col-md-2 mb-3">
                        <label>Start Time</label>
                        <input type="time"
                               name="start_time"
                               value="{{ $schedule->start_time }}"
                               class="form-control"
                               required>
                    </div>

                    <!-- END TIME -->
                    <div class="col-md-2 mb-3">
                        <label>End Time</label>
                        <input type="time"
                               name="end_time"
                               value="{{ $schedule->end_time }}"
                               class="form-control"
                               required>
                    </div>

                    <!-- ROOM -->
                    <div class="col-md-4 mb-3">
                        <label>Room</label>
                        <input type="text"
                               name="room"
                               value="{{ $schedule->room }}"
                               class="form-control"
                               placeholder="e.g. A101">
                    </div>

                </div>

                <button class="btn btn-warning">
                    <i class="bi bi-save"></i>
                    Update Schedule
                </button>

                <a href="{{ route('subject-schedules.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection