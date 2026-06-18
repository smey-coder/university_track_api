@extends('layouts.myapp')
@section('title', 'Department List')
@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">
            Department Management
        </h2>

        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add Department
        </a>
    </div>

    <!-- SUCCESS MESSAGE -->
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

    <!-- TABLE CARD -->
    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Department List</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Khmer Name</th>
                            <th>English Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th width="220">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($departments as $department)

                        <tr>

                            <td>{{ $department->id }}</td>

                            <td>{{ $department->department_code }}</td>

                            <td>{{ $department->department_name_khmer }}</td>

                            <td>{{ $department->department_name_english }}</td>

                            <td>{{ Str::limit($department->description, 50) }}</td>

                            <td>
                                @if(strtolower(trim($department->status)) === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center">

                                <a href="{{ route('departments.show', $department->id) }}"
                                   class="btn btn-info btn-sm">
                                    View
                                </a>

                                <a href="{{ route('departments.edit', $department->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('departments.destroy', $department->id) }}"
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
                            <td colspan="7" class="text-center text-muted">
                                No departments found.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- DELETE CONFIRM -->
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e){
        e.preventDefault();

        let form = this.closest('form');

        Swal.fire({
            title: 'Delete Department?',
            text: 'This department will be permanently deleted.',
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