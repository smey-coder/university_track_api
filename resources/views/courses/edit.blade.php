@extends('layouts.myapp')

@section('title', 'Edit Course')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-lg border-0">

                <!-- HEADER -->
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square"></i>
                        Update Course
                    </h4>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('courses.update',$course->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <!-- LEFT -->
                            <div class="col-md-6">

                                <label class="form-label">Course Code</label>
                                <input name="course_code"
                                       value="{{ $course->course_code }}"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Course Name</label>
                                <input name="course_name"
                                       value="{{ $course->course_name }}"
                                       class="form-control mb-3"
                                       required>

                                <label class="form-label">Credits</label>
                                <input name="credits"
                                       type="number"
                                       value="{{ $course->credits }}"
                                       class="form-control mb-3">

                                <label class="form-label">Status</label>
                                <select name="status" class="form-control mb-3">

                                    <option value="Active"
                                        {{ $course->status == 'Active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="Inactive"
                                        {{ $course->status == 'Inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">

                                <label class="form-label">Department</label>
                                <select name="department_id"
                                        class="form-control mb-3">

                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}"
                                            {{ $course->department_id == $d->id ? 'selected' : '' }}>
                                            {{ $d->department_name_english }}
                                        </option>
                                    @endforeach

                                </select>

                                <label class="form-label">Teacher</label>
                                <select name="teacher_id"
                                        class="form-control mb-3">

                                    <option value="">-- Select Teacher --</option>

                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}"
                                            {{ $course->teacher_id == $t->id ? 'selected' : '' }}>

                                            {{ $t->first_name_english }}
                                            {{ $t->last_name_english }}

                                        </option>
                                    @endforeach

                                </select>

                                <label class="form-label">Description</label>
                                <textarea name="description"
                                          class="form-control mb-3"
                                          rows="5">{{ $course->description }}</textarea>

                            </div>

                        </div>

                        <!-- BUTTONS -->
                        <div class="d-flex justify-content-between mt-3">

                            <a href="{{ route('courses.index') }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-check-circle"></i>
                                Update Course

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection