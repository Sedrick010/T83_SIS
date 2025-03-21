@extends('layouts.pageTemplate')

@section('title', 'Enrollment History')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Enrollment History</h6>
                    <div class="me-3">
                        <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm bg-gradient-dark mb-0">
                            <i class="material-symbols-rounded text-sm">arrow_back</i>&nbsp;&nbsp;Back to Enrollments
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <!-- Filters -->
                <div class="px-3 mb-4">
                    <form action="{{ route('admin.enrollments.history') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Search Student</label>
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Academic Year</label>
                                <select name="academic_year" class="form-control">
                                    <option value="">All Years</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Semester</label>
                                <select name="semester" class="form-control">
                                    <option value="">All Semesters</option>
                                    <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="Summer" {{ request('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn bg-gradient-primary mt-3">Filter</button>
                            <a href="{{ route('admin.enrollments.history') }}" class="btn bg-gradient-secondary mt-3">Reset</a>
                        </div>
                    </form>
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Academic Details</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subjects</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dates</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $enrollment->user->name ?? 'Deleted User' }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    @if($enrollment->user && $enrollment->user->student)
                                                        {{ $enrollment->user->student->student_id_number }}
                                                    @else
                                                        <span class="text-danger">User Deleted</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $enrollment->academic_year }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $enrollment->semester }} Semester</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $enrollment->subjects->count() }} subjects</p>
                                        <p class="text-xs text-secondary mb-0">{{ $enrollment->getTotalUnits() }} units</p>
                                    </td>
                                    <td>
                                        @if($enrollment->deleted_at)
                                            <span class="badge badge-sm bg-gradient-danger">Deleted</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-{{ $enrollment->status === 'enrolled' ? 'success' : ($enrollment->status === 'completed' ? 'info' : 'warning') }}">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Created: {{ $enrollment->created_at->format('M d, Y') }}</p>
                                        @if($enrollment->deleted_at)
                                            <p class="text-xs text-danger mb-0">Deleted: {{ $enrollment->deleted_at->format('M d, Y') }}</p>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.enrollments.show', $enrollment) }}" 
                                           class="btn btn-info btn-sm px-3 py-1" 
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="top" 
                                           title="View Details">
                                            <i class="material-symbols-rounded text-sm">visibility</i>
                                        </a>
                                        @if($enrollment->deleted_at && !$enrollment->grades()->exists())
                                            <form action="{{ route('admin.enrollments.restore', $enrollment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-success btn-sm px-3 py-1"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Restore Enrollment"
                                                        onclick="return confirm('Are you sure you want to restore this enrollment?')">
                                                    <i class="material-symbols-rounded text-sm">restore</i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
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

    // Handle input focus for outline inputs
    const inputs = document.querySelectorAll('.input-group-outline input, .input-group-outline select');
    inputs.forEach(input => {
        if (input.value !== '') {
            input.parentElement.classList.add('is-filled');
        }
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('is-focused');
        });
        input.addEventListener('blur', () => {
            input.parentElement.classList.remove('is-focused');
            if (input.value !== '') {
                input.parentElement.classList.add('is-filled');
            } else {
                input.parentElement.classList.remove('is-filled');
            }
        });
    });
</script>
@endpush 