@extends('layouts.myapp')

@section('title', 'Course Detail')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-lg border-0">

                <!-- HEADER -->
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-journal-text"></i>
                        Course Detail
                    </h4>
                </div>

                <div class="card-body">

                    <!-- COURSE TITLE -->
                    <div class="text-center mb-4">

                        <h3 class="fw-bold">
                            {{ $course->course_name }}
                        </h3>

                        <span class="badge bg-secondary">
                            {{ $course->course_code }}
                        </span>

                    </div>

                    <!-- DETAILS -->
                    <table class="table table-bordered">

                        <tr>
                            <th>Course Name</th>
                            <td>{{ $course->course_name }}</td>
                        </tr>

                        <tr>
                            <th>Course Code</th>
                            <td>{{ $course->course_code }}</td>
                        </tr>

                        <tr>
                            <th>Department</th>
                            <td>{{ $course->department->department_name_english ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Teacher</th>
                            <td>
                                {{ $course->teacher->first_name_english ?? '-' }}
                                {{ $course->teacher->last_name_english ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Credits</th>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $course->credits }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if($course->status == 'Active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Description</th>
                            <td>{{ $course->description ?? '-' }}</td>
                        </tr>

                    </table>

                    <!-- BUTTON -->
                    <div class="d-flex justify-content-between">

                        <a href="{{ route('courses.index') }}"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-left"></i>
                            Back

                        </a>

                        <a href="{{ route('courses.edit',$course->id) }}"
                           class="btn btn-warning">

                            <i class="bi bi-pencil"></i>
                            Edit

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection