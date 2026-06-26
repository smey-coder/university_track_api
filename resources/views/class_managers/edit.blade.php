@extends('layouts.myapp')

@section('title', 'Edit Assignment')

@section('content')

<div class="container-fluid">

    <div class="card shadow border-0">

        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square"></i>
                Edit Assignment
            </h5>
        </div>

        <div class="card-body">
            <x-message />
            <form action="{{ route('class-managers.update', $classManager->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- STUDENT -->
                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <select name="student_id" class="form-control" required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                {{ $classManager->student_id == $student->id ? 'selected' : '' }}>
                                {{ $student->first_name_english }} {{ $student->last_name_english }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- CLASS -->
                <div class="mb-3">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-control" required>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ $classManager->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DATE -->
                <div class="mb-3">
                    <label class="form-label">Assigned Date</label>
                    <input type="date"
                           name="assigned_date"
                           value="{{ $classManager->assigned_date }}"
                           class="form-control"
                           required>
                </div>

                <!-- STATUS -->
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="Active" {{ $classManager->status == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="Inactive" {{ $classManager->status == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <button class="btn btn-warning">
                    <i class="bi bi-save"></i> Update
                </button>

                <a href="{{ route('class-managers.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection