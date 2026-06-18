@extends('layouts.myapp')
@section('title', 'Department Details')
@section('content')

<div class="container-fluid">

    <div class="card shadow-lg">

        <div class="card-header bg-info text-white">
            <h4 class="mb-0">🏢 Department Profile</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th>ID</th>
                    <td>{{ $department->id }}</td>
                </tr>

                <tr>
                    <th>Code</th>
                    <td>{{ $department->department_code }}</td>
                </tr>

                <tr>
                    <th>Name (Khmer)</th>
                    <td>{{ $department->department_name_khmer }}</td>
                </tr>

                <tr>
                    <th>Name (English)</th>
                    <td>{{ $department->department_name_english }}</td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td>{{ $department->description }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @if(strtolower(trim($department->status)) === 'Active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>

            </table>

            <div class="d-flex justify-content-between">

                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    Back
                </a>

                <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning">
                    Edit
                </a>

            </div>

        </div>

    </div>

</div>

@endsection