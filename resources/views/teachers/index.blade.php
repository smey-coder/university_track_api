@extends('layouts.myapp')
@section('title','Teacher Management')
@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-workspace text-primary"></i>
                Teacher Management
            </h2>
            <small class="text-muted">
                Manage all teachers in the University Track System
            </small>
        </div>

        <a href="{{ route('teachers.create') }}" class="btn btn-primary shadow">
            <i class="bi bi-plus-circle"></i>
            Add Teacher
        </a>

    </div>

    <!-- Statistics -->
    <div class="row mb-4">

        <div class="col-lg-4">

            <div class="card border-0 shadow-lg bg-primary text-white">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6>Total Teachers</h6>

                            <h2>{{ $teachers->total() }}</h2>

                        </div>

                        <i class="bi bi-people-fill fs-1"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-lg bg-success text-white">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6>Active</h6>

                            <h2>
                                {{ $teachers->where('status','Active')->count() }}
                            </h2>

                        </div>

                        <i class="bi bi-check-circle-fill fs-1"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-lg bg-danger text-white">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6>Inactive</h6>

                            <h2>
                                {{ $teachers->where('status','Inactive')->count() }}
                            </h2>

                        </div>

                        <i class="bi bi-x-circle-fill fs-1"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Table -->

    <div class="card shadow-lg border-0">

        <div class="card-header bg-white">

            <div class="row">

                <div class="col-md-6">

                    <h5 class="fw-bold">
                        Teacher List
                    </h5>

                </div>

                <div class="col-md-6">

                    <input
                        type="text"
                        id="search"
                        class="form-control"
                        placeholder="Search teacher..."
                    >

                </div>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle" id="teacherTable">

                    <thead class="table-dark">

                    <tr class="text-center">

                        <th>ID</th>

                        <th>Photo</th>

                        <th>Teacher Code</th>

                        <th>Name</th>

                        <th>Department</th>

                        <th>Phone</th>

                        <th>Status</th>

                        <th width="180">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($teachers as $teacher)

                    <tr>

                        <td class="text-center">
                            {{ $teacher->id }}
                        </td>

                        <td class="text-center">

                            @if($teacher->photo)

                                <img
                                    src="{{ asset('storage/'.$teacher->photo) }}"
                                    class="rounded-circle border shadow"
                                    width="60"
                                    height="60"
                                    style="object-fit:cover">

                            @else

                                <img
                                    src="{{ asset('images/default-user.png') }}"
                                    class="rounded-circle border shadow"
                                    width="60"
                                    height="60">

                            @endif

                        </td>

                        <td>

                            <span class="badge bg-secondary">

                                {{ $teacher->teacher_code }}

                            </span>

                        </td>

                        <td>

                            <strong>

                                {{ $teacher->first_name_english }}
                                {{ $teacher->last_name_english }}

                            </strong>

                            <br>

                            <small class="text-muted">

                                {{ $teacher->first_name_khmer }}
                                {{ $teacher->last_name_khmer }}

                            </small>

                        </td>

                        <td>

                            {{ $teacher->department->department_name_english ?? '-' }}

                        </td>

                        <td>

                            {{ $teacher->phone }}

                        </td>

                        <td>

                            @if($teacher->status=="Active")

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            <a
                                href="{{ route('teachers.show',$teacher->id) }}"
                                class="btn btn-info btn-sm">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a
                                href="{{ route('teachers.edit',$teacher->id) }}"
                                class="btn btn-warning btn-sm">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <form
                                action="{{ route('teachers.destroy',$teacher->id) }}"
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

                        <td colspan="8" class="text-center text-muted">

                            No Teacher Found.

                        </td>

                    </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $teachers->links('pagination::bootstrap-5') }}

            </div>

        </div>

    </div>

</div>

<script>

document.getElementById("search").addEventListener("keyup", function(){

    let value=this.value.toLowerCase();

    document.querySelectorAll("#teacherTable tbody tr").forEach(function(row){

        row.style.display=row.innerText.toLowerCase().includes(value)
            ?"":"none";

    });

});

document.querySelectorAll('.delete-btn').forEach(btn=>{

    btn.onclick=function(){

        let form=this.closest('form');

        Swal.fire({

            title:'Delete Teacher?',

            text:'This teacher will be deleted.',

            icon:'warning',

            showCancelButton:true,

            confirmButtonColor:'#dc3545',

            confirmButtonText:'Delete'

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    };

});

</script>

@endsection