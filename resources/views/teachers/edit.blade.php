@extends('layouts.myapp')

@section('title','Edit Teacher')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card shadow-lg border-0">

                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square"></i>
                        Edit Teacher
                    </h4>
                </div>

                <div class="card-body">

                    <form action="{{ route('teachers.update',$teacher->id) }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6">

                                <input type="text"
                                       name="teacher_code"
                                       value="{{ $teacher->teacher_code }}"
                                       class="form-control mb-3">

                                <select name="department_id"
                                        class="form-control mb-3">

                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}"
                                            {{ $teacher->department_id == $d->id ? 'selected' : '' }}>
                                            {{ $d->department_name_english }}
                                        </option>
                                    @endforeach

                                </select>

                                <input type="text"
                                       name="first_name_khmer"
                                       value="{{ $teacher->first_name_khmer }}"
                                       class="form-control mb-3">

                                <input type="text"
                                       name="last_name_khmer"
                                       value="{{ $teacher->last_name_khmer }}"
                                       class="form-control mb-3">

                                <select name="gender" class="form-control mb-3">
                                    <option {{ $teacher->gender=='Male'?'selected':'' }}>Male</option>
                                    <option {{ $teacher->gender=='Female'?'selected':'' }}>Female</option>
                                </select>

                            </div>

                            <div class="col-md-6">

                                <input type="text"
                                       name="first_name_english"
                                       value="{{ $teacher->first_name_english }}"
                                       class="form-control mb-3">

                                <input type="text"
                                       name="last_name_english"
                                       value="{{ $teacher->last_name_english }}"
                                       class="form-control mb-3">

                                <input type="text"
                                       name="phone"
                                       value="{{ $teacher->phone }}"
                                       class="form-control mb-3">

                                <input type="email"
                                       name="email"
                                       value="{{ $teacher->email }}"
                                       class="form-control mb-3">

                                <select name="status" class="form-control mb-3">
                                    <option value="Active" {{ $teacher->status=='Active'?'selected':'' }}>Active</option>
                                    <option value="Inactive" {{ $teacher->status=='Inactive'?'selected':'' }}>Inactive</option>
                                </select>

                            </div>

                            <div class="col-12">

                                <textarea name="address"
                                          class="form-control mb-3">{{ $teacher->address }}</textarea>

                                <input type="file"
                                       name="photo"
                                       class="form-control mb-3">

                                @if($teacher->photo)
                                    <img src="{{ asset('storage/'.$teacher->photo) }}"
                                         width="80"
                                         class="rounded">
                                @endif

                            </div>

                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('teachers.index') }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                            <button class="btn btn-success">

                                Update Teacher

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection