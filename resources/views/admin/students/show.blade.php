@extends('layouts.pageTemplate')

@section('title', 'Student Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Student Details</h6>
                    <div class="me-3">
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm bg-gradient-warning mb-0 me-2">
                            <i class="material-symbols-rounded text-sm">edit</i>&nbsp;&nbsp;Edit
                        </a>
                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm bg-gradient-danger mb-0" 
                                    onclick="return confirm('Are you sure you want to remove this student?')">
                                <i class="material-symbols-rounded text-sm">delete</i>&nbsp;&nbsp;Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="row px-4">
                    <!-- Student Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pb-0">
                                <h6 class="mb-0">Personal Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <h6 class="text-uppercase text-sm mb-1">Name</h6>
                                            <p class="text-sm mb-0">{{ $student->name }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <h6 class="text-uppercase text-sm mb-1">Student ID</h6>
                                            <p class="text-sm mb-0">{{ $student->student_number ?? 'Not assigned' }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <h6 class="text-uppercase text-sm mb-1">Email</h6>
                                            <p class="text-sm mb-0">{{ $student->email }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <h6 class="text-uppercase text-sm mb-1">Course</h6>
                                            <p class="text-sm mb-0">{{ $student->course }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <h6 class="text-uppercase text-sm mb-1">Year Level</h6>
                                            <p class="text-sm mb-0">
                                                @php
                                                    $suffix = match($student->year_level) {
                                                        1 => 'st',
                                                        2 => 'nd',
                                                        3 => 'rd',
                                                        default => 'th'
                                                    };
                                                @endphp
                                                {{ $student->year_level }}{{ $suffix }} Year
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pb-0">
                                <h6 class="mb-0">Academic Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <h6 class="text-uppercase text-sm mb-1">Status</h6>
                                            <span class="badge badge-sm {{ $student->status === 'active' ? 'bg-gradient-success' : 'bg-gradient-warning' }}">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </div>
                                        <div class="mb-4">
                                            <h6 class="text-uppercase text-sm mb-1">Date Added</h6>
                                            <p class="text-sm mb-0">{{ $student->created_at->format('F d, Y') }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <h6 class="text-uppercase text-sm mb-1">Last Updated</h6>
                                            <p class="text-sm mb-0">{{ $student->updated_at->format('F d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <a href="{{ route('admin.students.index') }}" class="btn bg-gradient-dark mb-0">
                            <i class="material-symbols-rounded text-sm">arrow_back</i>&nbsp;&nbsp;Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 