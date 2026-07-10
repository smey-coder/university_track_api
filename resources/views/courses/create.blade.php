@extends('layouts.myapp')

@section('title', 'Create Course')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-lg border-0">

                <!-- HEADER -->
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-journal-plus"></i>
                        Create New Course
                    </h4>
                </div>

                <div class="card-body">
                    <x-message />
                    <form method="POST" action="{{ route('courses.store') }}">
                        @csrf

                        <div class="row">

                            <!-- LEFT -->
                            <div class="col-md-6">

                                <label class="form-label">Course Code</label>
                                <input name="course_code"
                                       class="form-control mb-3"
                                       placeholder="e.g. CS101"
                                       required>

                                <label class="form-label">Course Name</label>
                                <input name="course_name"
                                       class="form-control mb-3"
                                       placeholder="e.g. Database System"
                                       required>

                                <label class="form-label">Credits</label>
                                <input name="credits"
                                       type="number"
                                       class="form-control mb-3"
                                       value="3"
                                       min="1"
                                       max="10">

                                <label class="form-label">Status</label>
                                <select name="status" class="form-control mb-3">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>

                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">

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

                                <label class="form-label">Teacher</label>
                                <select name="teacher_id"
                                        class="form-control mb-3">

                                    <option value="">-- Optional --</option>

                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}">
                                            {{ $t->first_name_english }}
                                            {{ $t->last_name_english }}
                                        </option>
                                    @endforeach

                                </select>

                                <label class="form-label">Description</label>
                                <textarea name="description"
                                          class="form-control mb-3"
                                          rows="5"
                                          placeholder="Course description..."></textarea>

                            </div>

                        </div>

                        <!-- BUTTONS -->
                        <div class="d-flex justify-content-between mt-3">

                            <a href="{{ route('courses.index') }}"
                               class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn btn-success">

                                <i class="bi bi-save"></i>
                                Save Course

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection