@extends('layouts.myapp')

@section('title', 'Edit Assignment')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow border-0">

                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square"></i>
                        Edit Assignment
                    </h4>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('assignments.update',$assignment->id) }}">
                        @csrf
                        @method('PUT')

                        <input name="assignment_code"
                               value="{{ $assignment->assignment_code }}"
                               class="form-control mb-3">

                        <input name="title"
                               value="{{ $assignment->title }}"
                               class="form-control mb-3">

                        <textarea name="description"
                                  class="form-control mb-3">{{ $assignment->description }}</textarea>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Course</label>
                                <select name="course_id" class="form-control">
                                    @foreach($courses as $c)
                                        <option value="{{ $c->id }}"
                                            {{ $assignment->course_id == $c->id ? 'selected' : '' }}>
                                            {{ $c->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Teacher</label>
                                <select name="teacher_id" class="form-control">
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}"
                                            {{ $assignment->teacher_id == $t->id ? 'selected' : '' }}>
                                            {{ $t->first_name_english }} {{ $t->last_name_english }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Due Date</label>
                                <input type="datetime-local"
                                       name="due_date"
                                       value="{{ $assignment->due_date }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Total Score</label>
                                <input type="number"
                                       name="total_score"
                                       value="{{ $assignment->total_score }}"
                                       class="form-control">
                            </div>

                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option {{ $assignment->status=='Open'?'selected':'' }}>Open</option>
                                <option {{ $assignment->status=='Closed'?'selected':'' }}>Closed</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>

                            <button class="btn btn-primary">
                                Update
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection