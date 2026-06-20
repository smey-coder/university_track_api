@extends('layouts.myapp')

@section('title','Subject Schedule')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="mb-0">Subject Schedule</h3>
            <small class="text-muted">Manage weekly class timetable</small>
        </div>

        <a href="{{ route('subject-schedules.create') }}"
           class="btn btn-primary">
            + Add Schedule
        </a>

    </div>

    <!-- TABLE CARD -->
    <div class="card shadow border-0">

        <div class="card-body table-responsive">

            <table class="table table-hover align-middle text-center">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Course</th>
                        <th>Class</th>
                        <th>Semester</th>
                        <th>Teacher</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($schedules as $s)

                    <tr>

                        <td>{{ $s->id }}</td>

                        <!-- COURSE -->
                        <td>
                            {{ $s->course->course_name ?? '-' }}
                        </td>

                        <!-- CLASS (FIXED) -->
                        <td>
                            {{ $s->class->class_name ?? '-' }}
                        </td>

                        <!-- SEMESTER -->
                        <td>
                            {{ $s->semester->semester_name ?? '-' }}
                        </td>

                        <!-- TEACHER (FIXED) -->
                        <td>
                            {{ $s->teacher->full_name_english ?? '-' }}
                        </td>

                        <!-- DAY -->
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $s->day_of_week }}
                            </span>
                        </td>

                        <!-- TIME -->
                        <td>
                            <span class="badge bg-secondary">
                                {{ $s->start_time }} - {{ $s->end_time }}
                            </span>
                        </td>

                        <!-- ROOM -->
                        <td>
                            {{ $s->room ?? '-' }}
                        </td>

                        <!-- ACTION -->
                        <td>

                            <a href="{{ route('subject-schedules.show',$s->id) }}"
                               class="btn btn-sm btn-info">
                                View
                            </a>

                            <a href="{{ route('subject-schedules.edit',$s->id) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('subject-schedules.destroy',$s->id) }}"
                                  method="POST"
                                  class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-sm btn-danger delete-btn">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-muted">
                            No schedules found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- DELETE CONFIRM -->
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        let form = this.closest('form');

        if (confirm("Delete this schedule?")) {
            form.submit();
        }
    });
});
</script>

@endsection