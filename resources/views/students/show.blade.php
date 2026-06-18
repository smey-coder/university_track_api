
@extends('layouts.myapp')

@section('title', 'Student Details')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-info text-white">
            <h4>Student Profile</h4>
        </div>

        <div class="card-body">

            <div class="row">

                <!-- Photo -->
                <div class="col-md-3 text-center">

                    @if($students->photo)
                        <img src="{{ asset('storage/'.$students->photo) }}"
                             class="img-fluid rounded-circle mb-3"
                             width="150">
                    @else
                        <img src="{{ asset('images/default-user.png') }}"
                             class="img-fluid rounded-circle mb-3"
                             width="150">
                    @endif

                </div>

                <!-- Info -->
                <div class="col-md-9">

                    <table class="table table-bordered">

                        <tr>
                            <th>Student Code</th>
                            <td>{{ $students->student_code }}</td>
                        </tr>

                        <tr>
                            <th>Name (Khmer)</th>
                            <td>{{ $students->first_name_khmer }} {{ $students->last_name_khmer }}</td>
                        </tr>

                        <tr>
                            <th>Name (English)</th>
                            <td>{{ $students->first_name_english }} {{ $students->last_name_english }}</td>
                        </tr>

                        <tr>
                            <th>Gender</th>
                            <td>{{ $students->gender }}</td>
                        </tr>

                        <tr>
                            <th>Department</th>
                            <td>{{ $students->department->department_name_english ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Date Of Birth</th>
                            <td>{{ $students->date_of_birth }}</td>
                        </tr>

                        <tr>
                            <th>Phone</th>
                            <td>{{ $students->phone }}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ $students->email }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $students->status }}
                                </span>
                            </td>
                        </tr>

                    </table>

                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        Back
                    </a>

                    <a href="{{ route('students.edit', $students->id) }}" class="btn btn-warning">
                        Edit
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

