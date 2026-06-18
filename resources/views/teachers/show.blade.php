@extends('layouts.myapp')

@section('title','Teacher Profile')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-badge"></i>
                        Teacher Profile
                    </h4>
                </div>

                <div class="card-body">

                    <div class="text-center mb-4">

                        @if($teacher->photo)
                            <img src="{{ asset('storage/'.$teacher->photo) }}"
                                 class="rounded-circle shadow"
                                 width="140"
                                 height="140"
                                 style="object-fit:cover;">
                        @else
                            <img src="{{ asset('images/default-user.png') }}"
                                 class="rounded-circle shadow"
                                 width="140"
                                 height="140">
                        @endif

                        <h4 class="mt-3">
                            {{ $teacher->first_name_english }}
                            {{ $teacher->last_name_english }}
                        </h4>

                        <span class="badge bg-primary">
                            {{ $teacher->teacher_code }}
                        </span>

                    </div>

                    <table class="table table-bordered">

                        <tr>
                            <th>Khmer Name</th>
                            <td>{{ $teacher->first_name_khmer }} {{ $teacher->last_name_khmer }}</td>
                        </tr>

                        <tr>
                            <th>Gender</th>
                            <td>{{ $teacher->gender }}</td>
                        </tr>

                        <tr>
                            <th>Department</th>
                            <td>{{ $teacher->department->department_name_english ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Phone</th>
                            <td>{{ $teacher->phone }}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ $teacher->email }}</td>
                        </tr>

                        <tr>
                            <th>Hire Date</th>
                            <td>{{ $teacher->hire_date }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if($teacher->status == 'Active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Address</th>
                            <td>{{ $teacher->address }}</td>
                        </tr>

                    </table>

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('teachers.index') }}"
                           class="btn btn-secondary">

                            Back

                        </a>

                        <a href="{{ route('teachers.edit',$teacher->id) }}"
                           class="btn btn-warning">

                            Edit

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection