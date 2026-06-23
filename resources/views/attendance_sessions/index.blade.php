@extends('layouts.myapp')
@section('title','Attendance Sessions')
@section('content')

<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold">
            <i class="bi bi-calendar-check text-primary"></i>
            Attendance Sessions
        </h2>

        <small class="text-muted">
            Manage attendance sessions for classes
        </small>

    </div>

    <a href="{{ route('attendance_sessions.create') }}"
       class="btn btn-primary shadow">

        <i class="bi bi-plus-circle"></i>
        Create Session

    </a>

</div>

<div class="row mb-4">

    <div class="col-md-4">

        <div class="card border-0 shadow bg-primary text-white">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6>Total Sessions</h6>

                        <h2>{{ $sessions->total() }}</h2>

                    </div>

                    <i class="bi bi-calendar-event fs-1"></i>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card border-0 shadow bg-success text-white">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6>Active</h6>

                        <h2>
                            {{ $sessions->where('status','active')->count() }}
                        </h2>

                    </div>

                    <i class="bi bi-check-circle-fill fs-1"></i>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card border-0 shadow bg-danger text-white">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6>Finished</h6>

                        <h2>
                            {{ $sessions->where('status','finished')->count() }}
                        </h2>

                    </div>

                    <i class="bi bi-lock-fill fs-1"></i>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card shadow border-0">

    <div class="card-header bg-white">

        <div class="row">

            <div class="col-md-6">

                <h5 class="fw-bold mb-0">
                    Session List
                </h5>

            </div>

            <div class="col-md-6">

                <input type="text"
                       id="search"
                       class="form-control"
                       placeholder="Search session...">

            </div>

        </div>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle"
                   id="sessionTable">

                <thead class="table-dark">

                <tr class="text-center">

                    <th>ID</th>
                    <th>Session Code</th>
                    <th>Attendance Code</th>
                    <th>Class</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th width="220">Action</th>

                </tr>

                </thead>

                <tbody>

                @forelse($sessions as $session)
                <tr>
                    <td class="text-center">
                        {{ $session->id }}
                    </td>
                    <td>
                        <span class="badge bg-primary">
                            {{ $session->session_code }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success">
                            {{ $session->attendance_code }}
                        </span>
                    </td>
                    <td>
                        {{ $session->class->class_name ?? '-' }}
                    </td>
                    <td>
                        {{ $session->course->course_name ?? '-' }}
                    </td>
                    <td>
                        {{ $session->session_date }}
                        <br>
                        <small class="text-muted">

                            {{ $session->start_time }}
                            -
                            {{ $session->end_time }}

                        </small>

                    </td>

                    <td>

                        @if($session->status=='active')
                            <span class="badge bg-success">
                                Active
                            </span>
                        @else
                            <span class="badge bg-danger">
                                Finished
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('attendance_sessions.show',$session->id) }}"
                           class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('attendance_sessions.edit',$session->id) }}"
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($session->status=='active')

                        <form action="{{ route('attendance_sessions.close',$session->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            <button class="btn btn-success btn-sm">
                                <i class="bi bi-lock"></i>
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('attendance_sessions.destroy',$session->id) }}"
                              method="POST"
                              class="d-inline delete-form">
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
                    <td colspan="8"
                        class="text-center text-muted">
                        No Session Found
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">

            {{ $sessions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
</div>
<script>

document.getElementById('search')
.addEventListener('keyup', function(){

    let value = this.value.toLowerCase();

    document
    .querySelectorAll('#sessionTable tbody tr')
    .forEach(function(row){

        row.style.display =
            row.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';

    });

});

document.querySelectorAll('.delete-btn')
.forEach(btn => {

    btn.onclick = function(){

        let form =
            this.closest('form');

        Swal.fire({

            title:'Delete Session?',

            text:'This action cannot be undone.',

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
