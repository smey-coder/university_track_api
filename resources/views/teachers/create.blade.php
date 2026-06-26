@extends('layouts.myapp')

@section('title','Create Teacher')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card shadow-lg border-0">

                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square"></i>
                        Create Teacher
                    </h4>
                </div>

                <div class="card-body">

                    <x-message />

                    <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="row">

                            <div class="col-md-6">

                                <label class="form-label">Teacher Code</label>
                                <input type="text"
                                       name="teacher_code"
                                       placeholder="Enter teacher code"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Department</label>
                                <select name="department_id"
                                        class="form-control mb-3"
                                        required>
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}">
                                            {{ $d->department_name_english }}
                                        </option>
                                    @endforeach

                                </select>

                                <label class="form-label">First Name (Khmer)</label>
                                <input type="text"
                                       name="first_name_khmer"
                                       placeholder="First name in Khmer"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Last Name (Khmer)</label>
                                <input type="text"
                                       name="last_name_khmer"
                                       placeholder="Last name in Khmer"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control mb-3" required>
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">First Name (English)</label>
                                <input type="text"
                                       name="first_name_english"
                                       placeholder="First name in English"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Last Name (English)</label>
                                <input type="text"
                                       name="last_name_english"
                                       placeholder="Last name in English"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Phone</label>
                                <input type="text"
                                       name="phone"
                                       placeholder="Phone number"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       placeholder="Email address"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Status</label>
                                <select name="status" class="form-control mb-3" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>

                            </div>

                            <div class="col-12">

                                <label class="form-label">Address</label>
                                <textarea name="address"
                                          placeholder="Enter address"
                                          class="form-control mb-3"></textarea>

                                <label class="form-label">Photo</label>
                                <input type="file"
                                       name="photo"
                                       class="form-control mb-3"
                                       accept="image/*">

                            </div>

                        </div>

                        <div class="d-flex justify-content-between mt-4">

                            <a href="{{ route('teachers.index') }}"
                               class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>

                            <button class="btn btn-success" type="submit">
                                <i class="bi bi-save"></i> Create Teacher
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection