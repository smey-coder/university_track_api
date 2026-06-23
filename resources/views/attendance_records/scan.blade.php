@extends('layouts.myapp')

@section('title','Scan Attendance')

@section('content')

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="text-center mb-4">

        <h3 class="fw-bold">📱 Attendance Scanner</h3>

        <p class="text-muted">
            Enter student + scan or input attendance code
        </p>

    </div>

    <div class="row justify-content-center">

        <div class="col-md-6">

            <!-- CARD -->
            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Mark Attendance</h5>
                </div>

                <div class="card-body">

                    <!-- ALERT -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- FORM -->
                    <form method="POST" action="{{ route('attendances.scan.process') }}">
                        @csrf

                        <!-- STUDENT INPUT (IMPORTANT) -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                👨‍🎓 Student
                            </label>

                            <select name="student_id" class="form-select form-select-lg" required>

                                <option value="">Select Student</option>

                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->student_code }} -
                                        {{ $student->first_name_english }}
                                        {{ $student->last_name_english }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <!-- ATTENDANCE CODE -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                🔑 Attendance Code / QR Scan
                            </label>

                            <input type="text"
                                   name="attendance_code"
                                   class="form-control form-control-lg text-center"
                                   placeholder="Scan QR or enter code (ATT-XXXXXX)"
                                   autofocus
                                   required>

                            <small class="text-muted">
                                ✔ You can scan QR or type code manually
                            </small>

                        </div>

                        <!-- INFO BOX -->
                        <div class="alert alert-info py-2">
                            📌 Make sure student is selected before scanning attendance code
                        </div>

                        <!-- BUTTON -->
                        <button class="btn btn-success w-100 btn-lg">
                            ✔ Mark Attendance
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection