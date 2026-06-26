@extends('layouts.myapp')
@section('title', 'Assign Student')
@section('content')
<div class="container-fluid">
    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-person-plus"></i>
                Assign Student to Class
            </h5>
        </div>
        <div class="card-body">
            <x-message />
            <form action="{{ route('class-managers.store') }}" method="POST">
                @csrf
                <!-- STUDENT -->
                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <select name="student_id" class="form-control" required>
                        <option value="">-- Select Student --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->first_name_english }} {{ $student->last_name_english }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- CLASS -->
                <div class="mb-3">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-control" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DATE -->
                <div class="mb-3">
                    <label class="form-label">Assigned Date</label>
                    <input type="date" name="created_at" class="form-control" required>
                </div>

                <!-- STATUS -->
                {{-- <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div> --}}

                <button class="btn btn-primary">
                    <i class="bi bi-save"></i> Save
                </button>

                <a href="{{ route('class-managers.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection