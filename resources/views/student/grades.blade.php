@extends('layouts.studentTemplate')
@section('title', 'My Grades')
@section('content')
<div class="row">
    <!-- Grades by Semester -->
    @forelse($gradesBySemester as $yearGroup)
        @foreach($yearGroup as $semester)
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">
                            {{ $semester['academic_year'] }} - {{ $semester['semester'] }} Semester
                        </h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subject</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Units</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Grade</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($semester['enrollments'] as $enrollment)
                                    @foreach($enrollment->subjects as $subject)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $subject->code }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $subject->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $subject->units }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $enrollment->grades->where('subject_id', $subject->id)->first()?->grade ?? '-' }}
                                                </p>
                                            </td>
                                            <td>
                                                @php
                                                    $grade = $enrollment->grades->where('subject_id', $subject->id)->first()?->grade;
                                                    if ($grade === 'INC') {
                                                        $remarks = 'Incomplete';
                                                        $badgeClass = 'bg-gradient-warning'; 
                                                    } else if ($grade) {
                                                        $remarks = ((float)$grade <= 3.00) ? 'Passed' : 'Failed';
                                                        $badgeClass = ((float)$grade <= 3.00) ? 'bg-gradient-success' : 'bg-gradient-danger';
                                                    } else {
                                                        $remarks = '-';
                                                        $badgeClass = 'bg-gradient-secondary';
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $remarks }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <p class="mb-0">No grades found.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection 