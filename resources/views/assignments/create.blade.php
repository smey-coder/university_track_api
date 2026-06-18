@extends('layouts.myapp')

@section('title', 'Create Assignment')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow border-0">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-journal-plus"></i>
                        Create Assignment
                    </h4>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('assignments.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Assignment Code</label>
                            <input name="assignment_code" class="form-control" placeholder="ASG-001">
                        </div>

                        <div class="mb-3">
                            <label>Title</label>
                            <input name="title" class="form-control" placeholder="Assignment Title">
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Course</label>
                                <select name="course_id" class="form-control">
                                    @foreach($courses as $c)
                                        <option value="{{ $c->id }}">
                                            {{ $c->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Teacher</label>
                                <select name="teacher_id" class="form-control">
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}">
                                            {{ $t->first_name_english }} {{ $t->last_name_english }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Due Date</label>
                                <input type="datetime-local" name="due_date" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Total Score</label>
                                <input type="number" name="total_score" value="100" class="form-control">
                            </div>

                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option>Open</option>
                                <option>Closed</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>

                            <button class="btn btn-success">
                                Save Assignment
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection