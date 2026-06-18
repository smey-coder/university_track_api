@extends('layouts.myapp')
@section('title', 'Edit Department')
@section('content')

<div class="container-fluid">

    <div class="card shadow-lg">

        <div class="card-header bg-warning">
            <h4 class="mb-0">✏ Edit Department</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('departments.update', $department->id) }}">
                @csrf
                @method('PUT')

                <!-- CODE -->
                <div class="mb-3">
                    <label>Department Code</label>
                    <input type="text"
                           name="department_code"
                           class="form-control"
                           value="{{ $department->department_code }}">
                </div>

                <!-- KHMER -->
                <div class="mb-3">
                    <label>Name (Khmer)</label>
                    <input type="text"
                           name="department_name_khmer"
                           class="form-control"
                           value="{{ $department->department_name_khmer }}">
                </div>

                <!-- ENGLISH -->
                <div class="mb-3">
                    <label>Name (English)</label>
                    <input type="text"
                           name="department_name_english"
                           class="form-control"
                           value="{{ $department->department_name_english }}">
                </div>

                <!-- DESCRIPTION -->
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control">{{ $department->description }}</textarea>
                </div>

                <!-- STATUS -->
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">

                        <option value="Active"
                            {{ $department->status == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="Inactive"
                            {{ $department->status == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                <!-- BUTTONS -->
                <div class="d-flex justify-content-between">

                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button class="btn btn-success">
                        Update Department
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection