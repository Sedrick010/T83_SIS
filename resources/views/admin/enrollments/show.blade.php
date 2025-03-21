@extends('layouts.pageTemplate')
@section('title', 'Enrollment Details')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Enrollment Details</h6>
                    <div class="me-3">
                        <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="btn btn-sm bg-gradient-warning mb-0 me-2">
                            <i class="material-symbols-rounded text-sm">edit</i>&nbsp;&nbsp;Edit
                        </a>
                        <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm bg-gradient-danger mb-0" 
                                    onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                <i class="material-symbols-rounded text-sm">delete</i>&nbsp;&nbsp;Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Student Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-sm">Student Information</h6>
                        <div class="mb-4">
                            <p class="mb-0 text-sm"><strong>Name:</strong> {{ $enrollment->user->name }}</p>
                            <p class="mb-0 text-sm"><strong>Student Number:</strong> {{ $enrollment->user->student_number }}</p>
                            <p class="mb-0 text-sm"><strong>Course:</strong> {{ $enrollment->user->course }}</p>
                            <p class="mb-0 text-sm"><strong>Year Level:</strong> {{ $enrollment->user->year_level }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-sm">Enrollment Information</h6>
                        <div class="mb-4">
                            <p class="mb-0 text-sm"><strong>Academic Year:</strong> {{ $enrollment->academic_year }}</p>
                            <p class="mb-0 text-sm"><strong>Semester:</strong> {{ $enrollment->semester }}</p>
                            <p class="mb-0 text-sm"><strong>Status:</strong> 
                                <span class="badge badge-sm {{ $enrollment->status === 'enrolled' ? 'bg-gradient-success' : ($enrollment->status === 'dropped' ? 'bg-gradient-danger' : 'bg-gradient-info') }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </p>
                            <p class="mb-0 text-sm"><strong>Total Units:</strong> {{ $enrollment->getTotalUnits() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subject Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6 class="text-uppercase text-sm mb-0">Enrolled Subjects</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Units</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($enrollment->subjects as $subject)
                                                <tr>
                                                    <td class="align-middle">
                                                        <p class="text-sm font-weight-bold mb-0 px-3">{{ $subject->code }}</p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <p class="text-sm mb-0">{{ $subject->name }}</p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <p class="text-sm text-secondary mb-0">{{ $subject->description }}</p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <p class="text-sm mb-0">{{ $subject->units }}</p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge badge-sm {{ $subject->pivot->status === 'enrolled' ? 'bg-gradient-success' : ($subject->pivot->status === 'dropped' ? 'bg-gradient-danger' : 'bg-gradient-info') }}">
                                                            {{ ucfirst($subject->pivot->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($enrollment->notes)
                    <!-- Notes -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-sm">Notes</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-sm mb-0">{{ $enrollment->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12">
                        <a href="{{ route('admin.enrollments.index') }}" class="btn bg-gradient-dark mb-0">
                            <i class="material-symbols-rounded text-sm">arrow_back</i>&nbsp;&nbsp;Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 