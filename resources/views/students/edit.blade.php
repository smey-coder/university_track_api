@extends('layouts.myapp')

@section('title', 'Edit Student')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-warning text-dark">
            <h4>Edit Student</h4>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('students.update', $student->id) }}"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <!-- Student Code -->
                <div class="mb-3">
                    <label class="form-label">Student Code</label>
                    <input type="text"
                           name="student_code"
                           class="form-control"
                           value="{{ old('student_code', $student->student_code) }}"
                           required>
                </div>

                <!-- Department -->
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-control" required>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ $student->department_id == $dept->id ? 'selected' : '' }}>
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
                                {{ $student->class_id == $cl->id ? 'selected' : '' }}>
                                {{ $cl->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Names -->
                <div class="mb-3">
                    <label>First Name (Khmer)</label>
                    <input type="text" name="first_name_khmer"
                           class="form-control"
                           value="{{ $student->first_name_khmer }}">
                </div>

                <div class="mb-3">
                    <label>Last Name (Khmer)</label>
                    <input type="text" name="last_name_khmer"
                           class="form-control"
                           value="{{ $student->last_name_khmer }}">
                </div>

                <div class="mb-3">
                    <label>First Name (English)</label>
                    <input type="text" name="first_name_english"
                           class="form-control"
                           value="{{ $student->first_name_english }}">
                </div>

                <div class="mb-3">
                    <label>Last Name (English)</label>
                    <input type="text" name="last_name_english"
                           class="form-control"
                           value="{{ $student->last_name_english }}">
                </div>

                <!-- Gender -->
                <div class="mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- DOB -->
                <div class="mb-3">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ $student->date_of_birth }}">
                </div>

                <!-- Contact -->
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone"
                           class="form-control"
                           value="{{ $student->phone }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email"
                           class="form-control"
                           value="{{ $student->email }}">
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Pending" {{ $student->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Graduated" {{ $student->status == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="Suspended" {{ $student->status == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <!-- Photo -->
                <div class="mb-3">
                    <label>Photo</label><br>

                    @if($student->photo)
                        <img src="{{ asset('storage/'.$student->photo) }}"
                             width="80"
                             class="mb-2 rounded">
                    @endif

                    <input type="file" name="photo" class="form-control">
                </div>

                <!-- Buttons -->
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>

            </form>

        </div>

    </div>

</div>

@endsection
