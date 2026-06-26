@extends('layouts.myapp')

@section('title', 'Schedule Details')

@section('content')

<div class="container py-4">

    <div class="card shadow border-0">

        <div class="card-header bg-info text-white">

            <h4 class="mb-0">
                <i class="bi bi-calendar-week"></i>
                Subject Schedule Details
            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                <!-- Course -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Course</label>

                    <div class="form-control bg-light">
                        {{ $schedule->course->course_name ?? '-' }}
                    </div>
                </div>

                <!-- Class -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Class</label>

                    <div class="form-control bg-light">
                        {{ $schedule->class->class_name ?? '-' }}
                    </div>
                </div>

                <!-- Semester -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Semester</label>

                    <div class="form-control bg-light">
                        {{ $schedule->semester->semester_name ?? '-' }}
                    </div>
                </div>

                <!-- Teacher -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Teacher</label>

                    <div class="form-control bg-light">
                        {{ $schedule->teacher->full_name_english ?? '-' }}
                    </div>
                </div>

                <!-- Day -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Day</label>

                    <div>
                        <span class="badge bg-primary fs-6">
                            {{ $schedule->day_of_week }}
                        </span>
                    </div>
                </div>

                <!-- Time -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Class Time</label>

                    <div class="form-control bg-light">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                        -
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                    </div>
                </div>

                <!-- Room -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Room</label>

                    <div class="form-control bg-light">
                        {{ $schedule->room ?? '-' }}
                    </div>
                </div>

                <!-- Max Students -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Maximum Students</label>

                    <div class="form-control bg-light">
                        {{ $schedule->max_students ?? 0 }}
                    </div>
                </div>

                <!-- Start Date -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Start Date</label>

                    <div class="form-control bg-light">
                        {{ $schedule->start_date ? $schedule->start_date->format('d M Y') : '-' }}
                    </div>
                </div>

                <!-- End Date -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">End Date</label>

                    <div class="form-control bg-light">
                        {{ $schedule->end_date ? $schedule->end_date->format('d M Y') : '-' }}
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Status</label>

                    <div>

                        @if($schedule->status == 'active')

                            <span class="badge bg-success fs-6">
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger fs-6">
                                Finished
                            </span>

                        @endif

                    </div>
                </div>

            </div>

            <hr>

            <div class="text-end">

                <a href="{{ route('subject-schedules.edit', $schedule->id) }}"
                   class="btn btn-warning">

                    <i class="bi bi-pencil-square"></i>
                    Edit

                </a>

                <a href="{{ route('subject-schedules.index') }}"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Back

                </a>

            </div>

        </div>

    </div>

</div>

@endsection