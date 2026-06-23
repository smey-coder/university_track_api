@extends('layouts.myapp')
@section('title','Attendance Session Detail')
@section('content')
<div class="container-fluid">
<div class="row">

    <!-- LEFT INFO -->
    <div class="col-md-8">

        <div class="card shadow border-0 mb-3">

            <div class="card-header bg-primary text-white">

                <h4>
                    <i class="bi bi-info-circle"></i>
                    Session Information
                </h4>

            </div>

            <div class="card-body">

                <p><b>Session Code:</b> {{ $session->session_code }}</p>
                <p><b>Attendance Code:</b> {{ $session->attendance_code }}</p>
                <p><b>Class:</b> {{ $session->class->class_name ?? '-' }}</p>
                <p><b>Course:</b> {{ $session->course->course_name ?? '-' }}</p>
                <p><b>Teacher:</b> {{ $session->teacher->first_name_english ?? '' }} {{ $session->teacher->last_name_english ?? '' }}</p>
                <p><b>Date:</b> {{ $session->session_date }}</p>
                <p><b>Time:</b> {{ $session->start_time }} - {{ $session->end_time }}</p>

                <p>
                    <b>Status:</b>

                    @if($session->status=='active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Finished</span>
                    @endif
                </p>

            </div>

        </div>

        <!-- ATTENDANCE LIST -->
        <div class="card shadow border-0">

            <div class="card-header bg-dark text-white">

                <h5>Attendance List</h5>

            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Student</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($session->attendances as $att)

                            <tr>

                                <td>
                                    {{ $att->student->first_name_english ?? '' }} {{ $att->student->last_name_english ?? '' }}
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ $att->status }}
                                    </span>
                                </td>

                                <td>{{ $att->check_in }}</td>
                                <td>{{ $att->check_out }}</td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="col-md-4">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white">
                <h5>QR Code</h5>
            </div>
            <div class="card-body text-center">
                {{-- QR Code --}}
                <div class="mb-3">
                    {!! QrCode::size(180)->generate($session->attendance_code) !!}

                </div>

                <p><b>Code:</b> {{ $session->attendance_code }}</p>

                <form action="{{ route('attendance_sessions.regenerate',$session->id) }}"
                      method="POST">

                    @csrf

                    <button class="btn btn-warning btn-sm w-100 mb-2">
                        Regenerate Code
                    </button>

                </form>

                <form action="{{ route('attendance_sessions.close',$session->id) }}"
                      method="POST">

                    @csrf

                    <button class="btn btn-danger btn-sm w-100">
                        Close Session
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
</div>

@endsection
