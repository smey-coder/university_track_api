@extends('layouts.myapp')

@section('title', 'Assignment Detail')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow">

                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-eye"></i>
                        Assignment Detail
                    </h4>
                </div>

                <div class="card-body">

                    <h3 class="fw-bold">{{ $assignment->title }}</h3>

                    <span class="badge bg-secondary mb-3">
                        {{ $assignment->assignment_code }}
                    </span>

                    <table class="table table-bordered">

                        <tr>
                            <th>Course</th>
                            <td>{{ $assignment->course->course_name ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Teacher</th>
                            <td>
                                {{ $assignment->teacher->first_name_english ?? '-' }}
                                {{ $assignment->teacher->last_name_english ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Due Date</th>
                            <td>{{ $assignment->due_date }}</td>
                        </tr>

                        <tr>
                            <th>Total Score</th>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $assignment->total_score }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if($assignment->status == 'Open')
                                    <span class="badge bg-success">Open</span>
                                @else
                                    <span class="badge bg-danger">Closed</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Description</th>
                            <td>{{ $assignment->description }}</td>
                        </tr>

                    </table>

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <a href="{{ route('assignments.edit',$assignment->id) }}" class="btn btn-warning">
                            Edit
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection