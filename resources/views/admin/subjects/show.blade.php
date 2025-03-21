@extends('layouts.pageTemplate')
@section('title', 'Subject Details')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Subject Details</h6>
                </div>
            </div>
            <div class="card-body px-4 pb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-uppercase text-sm">Subject Code</h6>
                            <p class="text-lg mb-0">{{ $subject->code }}</p>
                        </div>
                        <div class="mb-4">
                            <h6 class="text-uppercase text-sm">Subject Name</h6>
                            <p class="text-lg mb-0">{{ $subject->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-uppercase text-sm">Units</h6>
                            <p class="text-lg mb-0">{{ $subject->units }}</p>
                        </div>
                        <div class="mb-4">
                            <h6 class="text-uppercase text-sm">Description</h6>
                            <p class="text-sm mb-0">{{ $subject->description }}</p>
                        </div>
                    </div>
                </div>

                @if($subject->enrollments->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Enrolled Students</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Academic Year</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Semester</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subject->enrollments as $enrollment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
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
                                                    <span class="badge badge-sm bg-gradient-{{ $enrollment->status === 'enrolled' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($enrollment->status) }}
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection