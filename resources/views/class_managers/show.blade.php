@extends('layouts.myapp')

@section('title', 'View Assignment')

@section('content')

<div class="container-fluid">

    <div class="card shadow border-0">

        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="bi bi-eye"></i>
                Assignment Detail
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <!-- Student -->
                <div class="col-md-6">
                    <h6>Student</h6>
                    <p>
                        {{ $classManager->student->first_name_english ?? '' }}
                        {{ $classManager->student->last_name_english ?? '' }}
                    </p>
                </div>

                <!-- Class -->
                <div class="col-md-6">
                    <h6>Class</h6>
                    <p>{{ $classManager->schoolClass->class_name ?? '-' }}</p>
                </div>

                <!-- Date -->
                <div class="col-md-6">
                    <h6>Assigned Date</h6>
                    <p>{{ $classManager->assigned_date }}</p>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <h6>Status</h6>
                    <p>
                        @if($classManager->status == 'Active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </p>
                </div>

            </div>

            <a href="{{ route('class-managers.index') }}" class="btn btn-secondary mt-3">
                Back
            </a>

        </div>

    </div>

</div>

@endsection