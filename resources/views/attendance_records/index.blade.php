@extends('layouts.myapp')

@section('title','Attendance List')

@section('content')

<div class="container-fluid py-3">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">📊 Attendance Records</h3>
            <small class="text-muted">QR Scan & Manual Attendance System</small>
        </div>

        <div class="d-flex gap-2">

    <!--  QR SCAN -->
    <a href="{{ route('attendances.scan.qr.page') }}"
       class="btn btn-success shadow-sm">

        <i class="bi bi-qr-code-scan"></i>
        QR Scan
    </a>
    <!--  MANUAL CODE SCAN -->
    <a href="{{ route('attendances.scan') }}"
       class="btn btn-primary shadow-sm">

        <i class="bi bi-keyboard"></i>
        Manual Scan

    </a>

</div>
    </div>
    <!-- QUICK INFO CARD -->
    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm bg-primary text-white">

                <div class="card-body">

                    <h6>Total Records</h6>
                    <h3>{{ $attendances->total() }}</h3>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm bg-success text-white">

                <div class="card-body">

                    <h6>Present</h6>
                    <h3>
                        {{ $attendances->where('status','Present')->count() }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-danger text-white">

                <div class="card-body">

                    <h6>Absent / Late</h6>
                    <h3>
                        {{ $attendances->where('status','!=','Present')->count() }}
                    </h3>

                </div>

            </div>

        </div>
    </div>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-8">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search Student, Course, Class, Attendance Code..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select
                            name="status"
                            class="form-select">
                            <option value="">All Status</option>

                            <option value="Present"
                                {{ request('status')=='Present'?'selected':'' }}>
                                Present
                            </option>

                            <option value="Late"
                                {{ request('status')=='Late'?'selected':'' }}>
                                Late
                            </option>

                            <option value="Absent"
                                {{ request('status')=='Absent'?'selected':'' }}>
                                Absent
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2 d-grid">

                        <button class="btn btn-primary">

                            <i class="bi bi-search"></i>

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <!-- TABLE CARD -->
    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Session</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($attendances as $a)

                        <tr>

                            <!-- ID -->
                            <td class="fw-bold">
                                {{ $a->id }}
                            </td>

                            <!-- STUDENT -->
                            <td>
                                <div class="fw-semibold">
                                    {{ $a->student->first_name_english ?? '-' }}
                                    {{ $a->student->last_name_english ?? '-' }}
                                </div>
                                <small class="text-muted">
                                   ID: {{ $a->student->student_code ?? '' }}
                                </small><br>
                                <small class="text-muted">
                                   Class: {{ $a->student->classes->class_name ?? '' }}
                                </small>
                            </td>

                            <!-- SESSION -->
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $a->session->session_code ?? '-' }}
                                </span>
                            </td>

                            <!-- COURSE -->
                            <td>
                                {{ $a->session->course->course_name ?? '-' }}
                            </td>

                            <!-- STATUS -->
                            <td>

                                @if(strtolower($a->status) == 'present')
                                    <span class="badge bg-success px-3 py-2">
                                        ✔ Present
                                    </span>

                                @elseif(strtolower($a->status) == 'late')
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        ⏰ Late
                                    </span>

                                @else
                                    <span class="badge bg-danger px-3 py-2">
                                        ❌ Absent
                                    </span>
                                @endif

                            </td>

                            <!-- CHECK IN -->
                            <td class="text-muted">
                                <i class="bi bi-clock"></i>
                                {{ $a->check_in ?? '-' }}
                            </td>

                            <!-- ACTION -->
                            <td class="text-center">

                                <a href="{{ route('attendance_records.show',$a->id) }}"
                                   class="btn btn-sm btn-info text-white">

                                    <i class="bi bi-eye"></i>

                                </a>

                                <a href="{{ route('attendance_records.edit',$a->id) }}"
                                   class="btn btn-sm btn-warning text-white">

                                    <i class="bi bi-pencil"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <!-- EMPTY STATE -->
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">

                                <div>
                                    <i class="bi bi-inbox display-4"></i>
                                    <h5 class="mt-2">No Attendance Found</h5>
                                    <p>Start by scanning QR code or adding attendance manually</p>
                                </div>

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- PAGINATION -->
    <div class="mt-3">
        {{ $attendances->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection