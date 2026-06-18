@extends('layouts.myapp')

@section('title', 'Course Management')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="fw-bold mb-0">
                <i class="bi bi-journal-bookmark text-primary"></i>
                Course List
            </h3>
            <small class="text-muted">Manage all university courses</small>
        </div>

        <a href="{{ route('courses.create') }}" class="btn btn-primary shadow">
            <i class="bi bi-plus-circle"></i>
            Add Course
        </a>

    </div>

    <!-- TABLE CARD -->
    <div class="card shadow-lg border-0">

        <div class="card-header bg-white">

            <input type="text"
                   id="search"
                   class="form-control"
                   placeholder="Search course...">

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle" id="courseTable">

                    <thead class="table-dark text-center">

                        <tr>

                            <th>ID</th>
                            <th>Code</th>
                            <th>Course Name</th>
                            <th>Department</th>
                            <th>Teacher</th>
                            <th>Credits</th>
                            <th>Status</th>
                            <th width="180">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($courses as $course)

                        <tr>

                            <td class="text-center">{{ $course->id }}</td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $course->course_code }}
                                </span>
                            </td>

                            <td>
                                <strong>{{ $course->course_name }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ Str::limit($course->description, 40) }}
                                </small>
                            </td>

                            <td>
                                {{ $course->department->department_name_english ?? '-' }}
                            </td>

                            <td>
                                {{ $course->teacher->first_name_english ?? '-' }}
                                {{ $course->teacher->last_name_english ?? '' }}
                            </td>

                            <td class="text-center">
                                <span class="badge bg-info">
                                    {{ $course->credits }}
                                </span>
                            </td>

                            <td class="text-center">

                                @if($course->status == 'Active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif

                            </td>

                            <td class="text-center">

                                <a href="{{ route('courses.show',$course->id) }}"
                                   class="btn btn-info btn-sm">

                                    <i class="bi bi-eye"></i>

                                </a>

                                <a href="{{ route('courses.edit',$course->id) }}"
                                   class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <form action="{{ route('courses.destroy',$course->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-danger btn-sm delete-btn">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="8" class="text-center text-muted">

                                No Courses Found

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>

// SEARCH
document.getElementById("search").addEventListener("keyup", function () {

    let value = this.value.toLowerCase();

    document.querySelectorAll("#courseTable tbody tr").forEach(row => {

        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ""
            : "none";

    });

});

// DELETE CONFIRM
document.querySelectorAll('.delete-btn').forEach(btn => {

    btn.onclick = function () {

        let form = this.closest('form');

        Swal.fire({

            title: 'Delete Course?',
            text: 'This course will be permanently removed!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'

        }).then((result) => {

            if (result.isConfirmed) {
                form.submit();
            }

        });

    };

});

</script>

@endsection