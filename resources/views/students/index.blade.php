@extends('layouts.myapp')
@section('title', 'Student List')
@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">
            Student Management
        </h2>

        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add Student
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session("success") }}',
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
    @endif

    <!-- Student Table -->
    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Student List</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Student Code</th>
                            <th>Khmer Name</th>
                            <th>English Name</th>
                            <th>Gender</th>
                            <th>Department</th>
                            <th>Class</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th width="220">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($students as $student)

                        <tr>

                            <td>{{ $student->id }}</td>

                            <td class="text-center">

                                @if($student->photo)

                                    <img src="{{ asset('storage/'.$student->photo) }}"
                                         width="50"
                                         height="50"
                                         class="rounded-circle">

                                @else

                                    <img src="{{ asset('images/default-user.png') }}"
                                         width="50"
                                         height="50"
                                         class="rounded-circle">

                                @endif

                            </td>

                            <td>{{ $student->student_code }}</td>

                            <td>
                                {{ $student->first_name_khmer }}
                                {{ $student->last_name_khmer }}
                            </td>

                            <td>
                                {{ $student->first_name_english }}
                                {{ $student->last_name_english }}
                            </td>

                            <td>{{ $student->gender }}</td>

                            <td>
                                {{ $student->department->department_name_english ?? '-' }}
                            </td>

                            <td>
                                {{ $student->classes->class_name ?? '-' }}
                            </td>

                            <td>{{ $student->phone }}</td>

                            <td>

                                @if($student->status=="Active")

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @elseif($student->status=="Pending")

                                    <span class="badge bg-warning">
                                        Pending
                                    </span>

                                @elseif($student->status=="Graduated")

                                    <span class="badge bg-primary">
                                        Graduated
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('students.show',$student->id) }}"
                                   class="btn btn-info btn-sm">
                                    View
                                </a>

                                <a href="{{ route('students.edit',$student->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('students.destroy',$student->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-danger btn-sm delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty

                        <tr>

                            <td colspan="10" class="text-center text-muted">
                                No students found.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $students->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>
</div>
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e){
        e.preventDefault();
        let form = this.closest('form');

        Swal.fire({

            title: 'Delete Student?',

            text: 'This student record will be permanently deleted.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#d33',

            cancelButtonColor: '#3085d6',

            confirmButtonText: 'Yes, Delete',

            cancelButtonText: 'Cancel'

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});

</script>

@endsection