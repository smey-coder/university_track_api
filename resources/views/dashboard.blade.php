@extends('layouts.myapp')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">
    <!-- WELCOME CARD -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <h4 class="mb-1">
                Welcome back, {{ Auth::user()->student()->last_name_english }} 👋
            </h4>
            <p class="text-muted mb-0">
                Manage students, departments, courses and academic system.
            </p>
        </div>
    </div>

    <!-- STATS -->
    <div class="row g-3">

        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>Students</h5>
                    <h2 class="fw-bold">120</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Departments</h5>
                    <h2 class="fw-bold">5</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h5>Courses</h5>
                    <h2 class="fw-bold">18</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- RECENT ACTIVITY -->
    <div class="card shadow border-0 mt-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Recent Activity</h5>
        </div>

        <div class="card-body">

            <ul class="list-group list-group-flush">

                <li class="list-group-item">✔ New student registered</li>
                <li class="list-group-item">✔ Department Computer Science updated</li>
                <li class="list-group-item">✔ Course "Database System" added</li>
                <li class="list-group-item">✔ Student activated account</li>

            </ul>

        </div>

    </div>

</div>

@endsection