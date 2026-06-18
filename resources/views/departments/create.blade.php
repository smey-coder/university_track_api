@extends('layouts.myapp')
@section('title', 'Create Department')
@section('content')

<div class="container-fluid">

    <div class="card shadow-lg">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">➕ Create Department</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('departments.store') }}">
                @csrf

                <!-- CODE -->
                <div class="mb-3">
                    <label>Department Code</label>
                    <input type="text" name="department_code" class="form-control" required>
                </div>

                <!-- KHMER NAME -->
                <div class="mb-3">
                    <label>Name (Khmer)</label>
                    <input type="text" name="department_name_khmer" class="form-control" required>
                </div>

                <!-- ENGLISH NAME -->
                <div class="mb-3">
                    <label>Name (English)</label>
                    <input type="text" name="department_name_english" class="form-control" required>
                </div>

                <!-- DESCRIPTION -->
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <!-- STATUS -->
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <!-- BUTTONS -->
                <div class="d-flex justify-content-between">

                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button class="btn btn-success">
                        Save Department
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection