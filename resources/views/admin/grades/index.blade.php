@extends('layouts.pageTemplate')

@section('title', 'Manage Grades')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Grades List</h6>
                    <a href="{{ route('admin.grades.create') }}" class="btn btn-sm bg-gradient-dark mb-0 me-3">
                        <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Add New Grade
                    </a>
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Academic Year</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Semester</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Grade</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grades as $grade)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                @if($grade->enrollment->user)
                                                    <h6 class="mb-0 text-sm">{{ $grade->enrollment->user->name }}</h6>
                                                    @if($grade->enrollment->user->student)
                                                        <p class="text-xs text-secondary mb-0">{{ $grade->enrollment->user->student->student_id_number }}</p>
                                                    @else
                                                        <p class="text-xs text-danger mb-0">Student record deleted</p>
                                                    @endif
                                                @else
                                                    <h6 class="mb-0 text-sm text-danger">Deleted Student</h6>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $subject = $grade->enrollment->subjects->where('id', $grade->subject_id)->first();
                                        @endphp
                                        @if($subject)
                                            <p class="text-xs font-weight-bold mb-0">{{ $subject->code }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $subject->name }}</p>
                                            @if($subject->trashed())
                                                <span class="badge badge-sm bg-gradient-warning">Subject Archived</span>
                                            @endif
                                        @else
                                            <p class="text-xs text-danger mb-0">Subject not found</p>
                                            <p class="text-xs text-secondary mb-0">ID: {{ $grade->subject_id }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $grade->enrollment->academic_year }}</p>
                                        @if($grade->enrollment->trashed())
                                            <span class="badge badge-sm bg-gradient-warning">Enrollment Archived</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $grade->enrollment->semester }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm {{ $grade->grade === 'INC' ? 'bg-gradient-warning' : ($grade->grade <= 3.00 ? 'bg-gradient-success' : 'bg-gradient-danger') }}">
                                            @if($grade->grade === 'INC')
                                                INC
                                            @else
                                                {{ number_format((float)$grade->grade, 2) }}
                                            @endif
                                        </span>
                                        @if($grade->remarks)
                                            <p class="text-xs text-secondary mb-0">{{ $grade->remarks }}</p>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.grades.show', $grade) }}" class="btn btn-info btn-sm px-3 py-1 me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                            <i class="material-symbols-rounded text-sm">visibility</i>
                                        </a>
                                        @if(!$grade->enrollment->trashed() && $grade->enrollment->user && $grade->enrollment->user->role === 'student')
                                            <a href="{{ route('admin.grades.edit', $grade) }}" class="btn btn-warning btn-sm px-3 py-1 me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Grade">
                                                <i class="material-symbols-rounded text-sm">edit</i>
                                            </a>
                                            <form action="{{ route('admin.grades.destroy', $grade) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm px-3 py-1" 
                                                        onclick="return confirm('Are you sure you want to delete this grade?')"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Grade">
                                                    <i class="material-symbols-rounded text-sm">delete</i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p class="text-sm mb-0">No grades found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-3 pt-4">
                    {{ $grades->links() }}
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