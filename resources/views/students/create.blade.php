@extends('layouts.myapp')
@section('title', 'Create Student')
@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Create Student</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Student Code -->
                <div class="mb-3">
                    <label class="form-label">Student Code</label>
                    <input type="text"
                           name="student_code"
                           class="form-control"
                           value="{{ old('student_code') }}"
                           required>
                    @error('student_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Department -->
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">
                                {{ $dept->department_name_english }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-control" required>
                        <option value="">-- Select Class --</option>
                        @foreach($class as $cl)
                            <option value="{{ $cl->id }}">
                                {{ $cl->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Khmer Name -->
                <div class="mb-3">
                    <label class="form-label">First Name (Khmer)</label>
                    <input type="text" name="first_name_khmer" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Name (Khmer)</label>
                    <input type="text" name="last_name_khmer" class="form-control" required>
                </div>

                <!-- English Name -->
                <div class="mb-3">
                    <label class="form-label">First Name (English)</label>
                    <input type="text" name="first_name_english" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Name (English)</label>
                    <input type="text" name="last_name_english" class="form-control" required>
                </div>

                <!-- Gender -->
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <!-- DOB -->
                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>

                <!-- Photo -->
                <div class="mb-3">
                    <label class="form-label">Photo</label>
                    <input type="file" name="photo" class="form-control">
                </div>

                <!-- Enrollment Date -->
                <div class="mb-3">
                    <label class="form-label">Enrollment Date</label>
                    <input type="date" name="enrollment_date" class="form-control">
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending">Pending</option>
                        <option value="Active">Active</option>
                        <option value="Graduated">Graduated</option>
                        <option value="Suspended">Suspended</option>
                    </select>
                </div>

                <!-- Buttons -->
                <button class="btn btn-success">
                    Save Student
                </button>

                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection