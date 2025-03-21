@extends('layouts.pageTemplate')

@section('title', 'Manage Enrollments')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Enrollments List</h6>
                    <div class="me-3">
                        <a href="{{ route('admin.enrollments.history') }}" class="btn btn-sm bg-gradient-dark mb-0 me-2">
                            <i class="material-symbols-rounded text-sm">history</i>&nbsp;&nbsp;View History
                        </a>
                        <a href="{{ route('admin.enrollments.create') }}" class="btn btn-sm bg-gradient-dark mb-0">
                            <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Add New Enrollment
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible text-white mx-3" role="alert">
                        <span class="text-sm">{{ session('success') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible text-white mx-3" role="alert">
                        <span class="text-sm">{{ session('error') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Academic Year</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Semester</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subjects</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $enrollment->user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $enrollment->user->student_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $enrollment->academic_year }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $enrollment->semester }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @foreach($enrollment->subjects as $subject)
                                                <div class="mb-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $subject->code }}</p>
                                                    <p class="text-xs text-secondary mb-0">{{ $subject->units }} units</p>
                                                </div>
                                            @endforeach
                                            <p class="text-xs text-info mb-0">Total Units: {{ $enrollment->getTotalUnits() }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm {{ $enrollment->status === 'enrolled' ? 'bg-gradient-success' : ($enrollment->status === 'dropped' ? 'bg-gradient-danger' : 'bg-gradient-info') }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="btn btn-info btn-sm px-3 py-1 me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                            <i class="material-symbols-rounded text-sm">visibility</i>
                                        </a>
                                        <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="btn btn-warning btn-sm px-3 py-1 me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Enrollment">
                                            <i class="material-symbols-rounded text-sm">edit</i>
                                        </a>
                                        <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm px-3 py-1" 
                                                    onclick="return confirm('Are you sure you want to delete this enrollment?')"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Enrollment">
                                                <i class="material-symbols-rounded text-sm">delete</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p class="text-sm mb-0">No enrollments found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-3 pt-4">
                    {{ $enrollments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush 