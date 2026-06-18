@extends('layouts.myapp')

@section('title','Assignment Submissions')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">

        <div>
            <h3 class="mb-0">
                <i class="bi bi-cloud-upload text-primary"></i>
                Submissions
            </h3>
            <small class="text-muted">Student assignment submissions</small>
        </div>

    </div>

    <div class="card shadow">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Student</th>
                        <th>Assignment</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($submissions as $s)

                    <tr>

                        <td>{{ $s->id }}</td>

                        <td>
                            <span class="badge bg-secondary">
                                {{ $s->submission_code }}
                            </span>
                        </td>

                        <td>
                            {{ $s->student->first_name_english ?? '-' }}
                            {{ $s->student->last_name_english ?? '' }}
                        </td>

                        <td>
                            {{ $s->assignment->title ?? '-' }}
                        </td>

                        <td>
                            {{ $s->submitted_at }}
                        </td>

                        <td>
                            @if($s->status == 'Submitted')
                                <span class="badge bg-primary">Submitted</span>
                            @elseif($s->status == 'Late')
                                <span class="badge bg-warning">Late</span>
                            @else
                                <span class="badge bg-success">Graded</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge bg-dark">
                                {{ $s->score ?? 'N/A' }}
                            </span>
                        </td>

                        <td>

                            <a href="{{ route('assignment_submissions.show',$s->id) }}"
                               class="btn btn-info btn-sm">
                                View
                            </a>

                            <a href="{{ route('assignment_submissions.edit',$s->id) }}"
                               class="btn btn-warning btn-sm">
                                Grade
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No submissions found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection