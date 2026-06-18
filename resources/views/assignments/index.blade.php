@extends('layouts.myapp')

@section('title','Assignments')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="mb-0">
                <i class="bi bi-journal-check text-primary"></i>
                Assignment List
            </h3>
            <small class="text-muted">Manage all assignments in the system</small>
        </div>

        <a href="{{ route('assignments.create') }}" class="btn btn-primary">
            + Add Assignment
        </a>

    </div>

    <!-- CARD -->
    <div class="card shadow border-0">

        <div class="card-body">

            <!-- TABLE -->
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Teacher</th>
                            <th>Due Date</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($assignments as $a)

                        <tr>

                            <td class="text-center">{{ $a->id }}</td>

                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    {{ $a->assignment_code }}
                                </span>
                            </td>

                            <td>
                                <strong>{{ $a->title }}</strong>
                            </td>

                            <td>
                                {{ $a->course->course_name ?? '-' }}
                            </td>

                            <td>
                                {{ $a->teacher->first_name_english ?? '-' }}
                                {{ $a->teacher->last_name_english ?? '' }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($a->due_date)->format('d M Y H:i') }}
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $a->total_score }}
                                </span>
                            </td>

                            <td>
                                @if($a->status == 'Open')
                                    <span class="badge bg-success">Open</span>
                                @else
                                    <span class="badge bg-danger">Closed</span>
                                @endif
                            </td>

                            <td class="text-center">

                                <a href="{{ route('assignments.show',$a->id) }}"
                                   class="btn btn-sm btn-info">
                                    View
                                </a>

                                <a href="{{ route('assignments.edit',$a->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('assignments.destroy',$a->id) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger delete-btn">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3"></i><br>
                                No assignments found
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection