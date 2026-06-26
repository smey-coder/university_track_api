@extends('layouts.myapp')

@section('title', 'Subject Schedule')

@section('content')

<div class="container-fluid py-4">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">
                <i class="bi bi-calendar-week"></i>
                Subject Schedule
            </h3>

            <small class="text-muted">
                Manage Subject Schedule Information
            </small>
        </div>

        <a href="{{ route('subject-schedules.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Add Schedule

        </a>

    </div>

    {{-- Card --}}
    <div class="card shadow border-0">

        <div class="card-header bg-white">

            <div class="row">

                <div class="col-md-6">
                    <h5 class="mb-0">
                        Schedule List
                    </h5>
                </div>

                <div class="col-md-6">

                    <form method="GET">

                        <div class="input-group">

                            <input
                                type="text"
                                class="form-control"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search Course, Class, Teacher...">

                            <button class="btn btn-primary">

                                <i class="bi bi-search"></i>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle text-center">

                <thead class="table-dark">

                <tr>

                    <th width="60">#</th>

                    <th>Course</th>

                    <th>Class</th>

                    <th>Semester</th>

                    <th>Teacher</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Duration</th>
                    <th>Status</th>

                    <th width="170">Action</th>

                </tr>

                </thead>

                <tbody>

                @forelse($schedules as $schedule)

                    <tr>

                        <td>
                            {{ $loop->iteration + ($schedules->currentPage()-1) * $schedules->perPage() }}
                        </td>

                        <td>
                            {{ $schedule->course->course_name ?? '-' }}
                        </td>

                        <td>
                            {{ $schedule->class->class_name ?? '-' }}
                        </td>

                        <td>
                            {{ $schedule->semester->semester_name ?? '-' }}
                        </td>

                        <td>
                            {{ $schedule->teacher->full_name_english ?? '-' }}
                        </td>

                        <td>

                            <span class="badge bg-primary">

                                {{ $schedule->day_of_week }}

                            </span>

                        </td>

                        <td>

                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}

                            <br>

                            <small class="text-muted">

                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}

                            </small>

                        </td>

                        <td>

                            {{ $schedule->room ?? '-' }}

                        </td>

                        <td>

                            @if($schedule->created_at)

                                {{ $schedule->created_at->format('d M Y') }}

                                <br>

                                <small class="text-muted">to</small>

                                <br>

                                {{ optional($schedule->updated_at)->format('d M Y') }}

                            @else

                                -

                            @endif

                        </td>
                        <td>

                            @if($schedule->status == 'active')

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Finished

                                </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('subject-schedules.show',$schedule->id) }}"
                               class="btn btn-info btn-sm">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a href="{{ route('subject-schedules.edit',$schedule->id) }}"
                               class="btn btn-warning btn-sm">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <form
                                action="{{ route('subject-schedules.destroy',$schedule->id) }}"
                                method="POST"
                                class="d-inline">

                                @csrf

                                @method('DELETE')

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm delete-btn">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty
                                    <tr>

                        <td colspan="12" class="text-center py-5">

                            <i class="bi bi-calendar-x fs-1 text-secondary"></i>

                            <h5 class="mt-3 text-secondary">
                                No Subject Schedule Found
                            </h5>

                            <p class="text-muted">
                                Click the <strong>Add Schedule</strong> button to create a new schedule.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <small class="text-muted">

                    Showing
                    {{ $schedules->firstItem() ?? 0 }}
                    -
                    {{ $schedules->lastItem() ?? 0 }}
                    of
                    {{ $schedules->total() }}
                    schedules

                </small>

                {{ $schedules->links() }}

            </div>

        </div>

    </div>

</div>

{{-- Delete Confirmation --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {

        button.addEventListener('click', function () {

            let form = this.closest('form');

            if (confirm('Are you sure you want to delete this schedule?')) {

                form.submit();

            }

        });

    });

});

</script>

@endsection