@extends('layouts.pageTemplate')
@section('title', 'Add Grade')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Add New Grade</h6>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
                        <span class="text-sm">{{ session('success') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
                        <span class="text-sm">{{ session('error') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.grades.store') }}" class="row g-3" id="gradeForm">
                    @csrf

                    <!-- Student Selection -->
                    <div class="col-md-12">
                        <div class="input-group input-group-static mb-4">
                            <label for="student_select" class="ms-0">Select Student</label>
                            <select class="form-control @error('enrollment_id') is-invalid @enderror" id="student_select" name="student_select" required>
                                <option value="">Choose a student</option>
                                @php
                                    $students = $enrollmentSubjects->groupBy('id')->map(function($group) {
                                        return [
                                            'id' => $group->first()['id'],
                                            'name' => $group->first()['student_name']
                                        ];
                                    });
                                @endphp
                                @foreach($students as $student)
                                    <option value="{{ $student['id'] }}">
                                        {{ $student['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('enrollment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Subject Selection -->
                    <div class="col-md-12" id="subject_selection" style="display: none;">
                        <div class="input-group input-group-static mb-4">
                            <label for="subject_select" class="ms-0">Select Subject</label>
                            <select class="form-control @error('subject_id') is-invalid @enderror" id="subject_select" name="subject_select" required>
                                <option value="">Choose a subject</option>
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Hidden fields for enrollment_id and subject_id -->
                    <input type="hidden" name="enrollment_id" id="enrollment_id">
                    <input type="hidden" name="subject_id" id="subject_id">

                    <!-- Grade Section -->
                    <div id="grade_section" class="row" style="display: none;">
                        <!-- Grade -->
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label for="grade" class="ms-0">Grade</label>
                                <select class="form-control @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                                    <option value="">Select Grade</option>
                                    <option value="1.00">1.00 - Excellent</option>
                                    <option value="1.25">1.25 - Excellent</option>
                                    <option value="1.50">1.50 - Very Good</option>
                                    <option value="1.75">1.75 - Very Good</option>
                                    <option value="2.00">2.00 - Good</option>
                                    <option value="2.25">2.25 - Good</option>
                                    <option value="2.50">2.50 - Fair</option>
                                    <option value="2.75">2.75 - Fair</option>
                                    <option value="3.00">3.00 - Passed</option>
                                    <option value="3.25">3.25 - Failed</option>
                                    <option value="3.50">3.50 - Failed</option>
                                    <option value="3.75">3.75 - Failed</option>
                                    <option value="4.00">4.00 - Failed</option>
                                    <option value="4.25">4.25 - Failed</option>
                                    <option value="4.50">4.50 - Failed</option>
                                    <option value="4.75">4.75 - Failed</option>
                                    <option value="5.00">5.00 - Failed</option>
                                    <option value="INC">INC - Incomplete</option>
                                </select>
                                @error('grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-muted mb-3">
                                Select grade (1.00-5.00). Passing grade is 3.00 or lower.
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="col-md-12">
                            <div class="input-group input-group-static mb-4">
                                <label for="remarks" class="ms-0">Remarks (Optional)</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3"></textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn bg-gradient-primary">Add Grade</button>
                            <a href="{{ route('admin.grades.index') }}" class="btn bg-gradient-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_select');
    const subjectSelect = document.getElementById('subject_select');
    const subjectSelection = document.getElementById('subject_selection');
    const gradeSection = document.getElementById('grade_section');
    const enrollmentIdInput = document.getElementById('enrollment_id');
    const subjectIdInput = document.getElementById('subject_id');
    const gradeForm = document.getElementById('gradeForm');

    // Store all enrollment subjects data
    const enrollmentSubjects = @json($enrollmentSubjects);

    // When student is selected
    studentSelect.addEventListener('change', function() {
        const enrollmentId = this.value;
        
        if (enrollmentId) {
            try {
                // Filter subjects for selected student
                const studentSubjects = enrollmentSubjects.filter(item => item.id.toString() === enrollmentId.toString());
                
                // Clear and populate subject dropdown
                subjectSelect.innerHTML = '<option value="">Choose a subject</option>';
                
                if (studentSubjects.length > 0) {
                    studentSubjects.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.subject_id;
                        option.textContent = `${item.subject_code} - ${item.subject_name}`;
                        subjectSelect.appendChild(option);
                    });
                    
                    // Show subject selection
                    subjectSelection.style.display = 'block';
                    // Hide grade section until subject is selected
                    gradeSection.style.display = 'none';
                    
                    // Set enrollment ID
                    enrollmentIdInput.value = enrollmentId;
                } else {
                    // Show no subjects message
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No subjects available for grading";
                    subjectSelect.appendChild(option);
                    subjectSelection.style.display = 'block';
                }
            } catch (error) {
                console.error('Error populating subjects:', error);
                // Show error message to user
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger text-white';
                alertDiv.textContent = 'Error loading subjects. Please try again.';
                gradeForm.insertBefore(alertDiv, subjectSelection);
            }
        } else {
            // Hide both sections if no student selected
            subjectSelection.style.display = 'none';
            gradeSection.style.display = 'none';
            enrollmentIdInput.value = '';
            subjectIdInput.value = '';
        }
    });

    // When subject is selected
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        
        if (subjectId) {
            // Set subject ID and show grade section
            subjectIdInput.value = subjectId;
            gradeSection.style.display = 'block';
        } else {
            // Hide grade section if no subject selected
            gradeSection.style.display = 'none';
            subjectIdInput.value = '';
        }
    });

    // Form validation
    gradeForm.addEventListener('submit', function(e) {
        if (!enrollmentIdInput.value || !subjectIdInput.value) {
            e.preventDefault();
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger text-white';
            alertDiv.textContent = 'Please select both a student and a subject.';
            gradeForm.insertBefore(alertDiv, gradeForm.firstChild);
            return false;
        }
    });
});
</script>
@endpush

@endsection 