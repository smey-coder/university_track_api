@extends('layouts.myapp')
@section('title', 'Class Manager')
@section('content')
<div class="container-fluid">
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon:'success',
                    title:'Success',
                    text:'{{ session("success") }}',
                    timer:2000,
                    showConfirmButton:false
                });
            });
        </script>
    @endif
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">
                <i class="bi bi-diagram-3-fill"></i>
                Class Manager
            </h2>
            <p class="text-muted mb-0">
                Manage students assigned to classes.
            </p>
        </div>

        <a href="{{ route('class-managers.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Assign Student

        </a>

    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                Student Class List
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>

                            <th>ID</th>

                            <th>Photo</th>

                            <th>Student</th>

                            <th>Student Code</th>

                            <th>Class</th>

                            <th>Assigned Date</th>

                            {{-- <th>Status</th> --}}

                            <th width="220">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($classManagers as $item)

                        <tr>

                            <td class="text-center">
                                {{ $item->id }}
                            </td>

                            <td class="text-center">

                                @if($item->student && $item->student->photo)

                                    <img src="{{ asset('storage/'.$item->student->photo) }}"
                                         width="55"
                                         height="55"
                                         class="rounded-circle border">

                                @else

                                    <img src="{{ asset('images/default-user.png') }}"
                                         width="55"
                                         height="55"
                                         class="rounded-circle border">

                                @endif

                            </td>

                            <td>

                                <strong>

                                    {{ $item->student->first_name_english ?? '' }}
                                    {{ $item->student->last_name_english ?? '' }}

                                </strong>

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $item->student->student_code ?? '-' }}

                                </span>

                            </td>

                            <td>

                                <span class="badge bg-info">

                                    {{ $item->StudentClass->class_name ?? '-' }}

                                </span>

                            </td>

                            <td>

                                {{ $item->created_at }}

                            </td>

                            {{-- <td>

                                @if($item->status=="Active")

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Inactive

                                    </span>

                                @endif

                            </td> --}}

                            <td class="text-center">

                                <a href="{{ route('class-managers.show',$item->id) }}"
                                   class="btn btn-info btn-sm">

                                    <i class="bi bi-eye"></i>

                                </a>

                                <a href="{{ route('class-managers.edit',$item->id) }}"
                                   class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <form action="{{ route('class-managers.destroy',$item->id) }}"
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

                            <td colspan="8"
                                class="text-center text-muted">

                                No class assignments found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $classManagers->links('pagination::bootstrap-5') }}

            </div>

        </div>

    </div>

</div>

<script>

document.querySelectorAll('.delete-btn').forEach(btn=>{

    btn.addEventListener('click',function(){

        let form=this.closest('form');

        Swal.fire({

            title:'Delete?',

            text:'Delete this assignment?',

            icon:'warning',

            showCancelButton:true,

            confirmButtonColor:'#d33',

            confirmButtonText:'Delete'

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});

</script>

@endsection