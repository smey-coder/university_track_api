@extends('layouts.myapp')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <!-- STATS -->
    <div class="row g-3">

        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>Students</h5>
                    <h2>{{ $totalStudents }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Departments</h5>
                    <h2>{{ $totalDepartments }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h5>Users</h5>
                    <h2>{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- RECENT STUDENTS -->
    <div class="card shadow border-0 mt-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Latest Students</h5>
        </div>

        <div class="card-body">

            <ul class="list-group">

                @foreach($latestStudents as $student)
                    <li class="list-group-item">
                        {{ $student->student_code }} -
                        {{ $student->first_name_english }}
                        {{ $student->last_name_english }}
                    </li>
                @endforeach

            </ul>

        </div>

    </div>

</div>

@endsection