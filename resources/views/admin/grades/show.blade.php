@extends('layouts.pageTemplate')
@section('title', 'Grade Details')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Grade Details</h6>
                    <div class="me-3">
                        <a href="{{ route('admin.grades.edit', $grade) }}" class="btn btn-sm bg-gradient-warning mb-0 me-2">
                            <i class="material-symbols-rounded text-sm">edit</i>&nbsp;&nbsp;Edit
                        </a>
                        <form action="{{ route('admin.grades.destroy', $grade) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm bg-gradient-danger mb-0" 
                                    onclick="return confirm('Are you sure you want to delete this grade?')">
                                <i class="material-symbols-rounded text-sm">delete</i>&nbsp;&nbsp;Delete
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
                                <h6 class="mb-0">Student Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <h6 class="text-sm mb-1">Name</h6>
                                            <p class="text-sm mb-0">{{ $grade->enrollment->user->name }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <h6 class="text-sm mb-1">Student ID</h6>
                                            <p class="text-sm mb-0">{{ $grade->enrollment->user->student_number }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <h6 class="text-sm mb-1">Email</h6>
                                            <p class="text-sm mb-0">{{ $grade->enrollment->user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subject Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pb-0">
                                <h6 class="mb-0">Subject Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        @php
                                            $subject = $grade->enrollment->subjects->where('id', $grade->subject_id)->first();
                                        @endphp
                                        <div class="mb-4">
                                            <h6 class="text-sm mb-1">Subject Code</h6>
                                            <p class="text-sm mb-0">{{ $subject->code }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <h6 class="text-sm mb-1">Subject Name</h6>
                                            <p class="text-sm mb-0">{{ $subject->name }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <h6 class="text-sm mb-1">Units</h6>
                                            <p class="text-sm mb-0">{{ $subject->units }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Information -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-3 pb-0">
                                <h6 class="mb-0">Grade Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <h6 class="text-sm mb-1">Grade</h6>
                                        <span class="badge badge-sm {{ $grade->grade === 'INC' ? 'bg-gradient-warning' : ($grade->grade <= 3.00 ? 'bg-gradient-success' : 'bg-gradient-danger') }}">
                                            @if($grade->grade === 'INC')
                                                INC
                                            @else
                                                {{ number_format((float)$grade->grade, 2) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <h6 class="text-sm mb-1">Status</h6>
                                        <span class="badge badge-sm {{ $grade->grade === 'INC' ? 'bg-gradient-warning' : ($grade->grade <= 3.00 ? 'bg-gradient-success' : 'bg-gradient-danger') }}">
                                            {{ $grade->getRemarks() }}
                                        </span>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <h6 class="text-sm mb-1">Date Recorded</h6>
                                        <p class="text-sm mb-0">{{ $grade->created_at->format('F d, Y') }}</p>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <h6 class="text-sm mb-1">Academic Year</h6>
                                        <p class="text-sm mb-0">{{ $grade->enrollment->academic_year }}</p>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <h6 class="text-sm mb-1">Semester</h6>
                                        <p class="text-sm mb-0">{{ $grade->enrollment->semester }}</p>
                                    </div>
                                    @if($grade->remarks)
                                        <div class="col-12">
                                            <h6 class="text-sm mb-1">Remarks</h6>
                                            <p class="text-sm mb-0">{{ $grade->remarks }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <a href="{{ route('admin.grades.index') }}" class="btn bg-gradient-dark mb-0">
                            <i class="material-symbols-rounded text-sm">arrow_back</i>&nbsp;&nbsp;Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 