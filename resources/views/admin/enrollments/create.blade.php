@extends('layouts.pageTemplate')
@section('title', 'Add New Enrollment')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Add New Enrollment</h6>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.enrollments.store') }}" class="row g-3" id="enrollmentForm">
                    @csrf

                    <!-- Student Selection -->
                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="student_id">Student</label>
                            <select class="form-control" id="student_id" name="user_id" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->student_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Academic Year -->
                    <div class="col-md-3">
                        <div class="input-group input-group-static mb-4">
                            <label for="academic_year">Academic Year</label>
                            <select class="form-control" id="academic_year" name="academic_year" required>
                                <option value="">Select Academic Year</option>
                                @php
                                    $currentYear = date('Y');
                                    for($i = 0; $i < 5; $i++) {
                                        $academicYear = ($currentYear - $i) . '-' . ($currentYear - $i + 1);
                                        echo '<option value="' . $academicYear . '" ' . (old('academic_year') == $academicYear ? 'selected' : '') . '>' . $academicYear . '</option>';
                                    }
                                @endphp
                            </select>
                            @error('academic_year')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Semester -->
                    <div class="col-md-3">
                        <div class="input-group input-group-static mb-4">
                            <label for="semester">Semester</label>
                            <select class="form-control" id="semester" name="semester" required>
                                <option value="">Select Semester</option>
                                <option value="1st" {{ old('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd" {{ old('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                                <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Selected Subjects Summary -->
                    <div class="col-12 mb-4">
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Selected Subjects:</strong> <span id="selectedSubjectsCount">0</span>
                                <br>
                                <strong>Total Units:</strong> <span id="totalUnits">0</span>
                            </div>
                            <div>
                                <span id="unitWarning" class="text-danger d-none">
                                    Exceeds recommended units (<span id="maxUnitsDisplay">24</span>)
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Available Subjects Grid -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-3">
                                <h6 class="mb-0">Available Subjects</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row" id="availableSubjects">
                                    @foreach($subjects as $subject)
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-3 position-relative">
                                                <div class="form-check">
                                                    <input class="form-check-input subject-checkbox" 
                                                           type="checkbox" 
                                                           name="subject_ids[]"
                                                           value="{{ $subject->id }}"
                                                           id="subject_{{ $subject->id }}"
                                                           data-code="{{ $subject->code }}"
                                                           data-name="{{ $subject->name }}"
                                                           data-units="{{ $subject->units }}"
                                                           {{ in_array($subject->id, old('subject_ids', [])) ? 'checked' : '' }}
                                                           onchange="updateSubjectSelection(this)">
                                                    <label class="form-check-label" for="subject_{{ $subject->id }}">
                                                        <h6 class="mb-1 text-sm">{{ $subject->code }}</h6>
                                                        <p class="mb-1 text-xs text-secondary">{{ $subject->name }}</p>
                                                        <small class="text-xs text-info">Units: {{ $subject->units }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('subject_ids')
                                    <span class="text-danger text-xs d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="enrolled" {{ old('status', 'enrolled') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <div class="input-group input-group-static mb-4">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn bg-gradient-primary">Create Enrollment</button>
                        <a href="{{ route('admin.enrollments.index') }}" class="btn bg-gradient-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize total units and selected count
        updateTotalUnits();
        updateSelectedCount();
        
        // Initialize all select elements
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            if (select.classList.contains('form-control')) {
                const parent = select.closest('.input-group-static');
                if (parent) {
                    select.addEventListener('change', function() {
                        if (this.value) {
                            parent.classList.add('is-filled');
                        } else {
                            parent.classList.remove('is-filled');
                        }
                    });
                    
                    // Set initial state
                    if (select.value) {
                        parent.classList.add('is-filled');
                    }
                }
            }
        });

        // Add change event listener to semester dropdown
        const semesterSelect = document.getElementById('semester');
        if (semesterSelect) {
            semesterSelect.addEventListener('change', updateTotalUnits);
        }

        // Add submit event listener to the form
        const enrollmentForm = document.getElementById('enrollmentForm');
        if (enrollmentForm) {
            enrollmentForm.addEventListener('submit', function(e) {
                const checkedSubjects = document.querySelectorAll('input[name="subject_ids[]"]:checked');
                if (checkedSubjects.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one subject for enrollment.');
                    return false;
                }
                return true;
            });
        }
    });

    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('input[name="subject_ids[]"]:checked').length;
        const countDisplay = document.getElementById('selectedSubjectsCount');
        if (countDisplay) {
            countDisplay.textContent = selectedCount;
        }
    }

    function updateTotalUnits() {
        const checkedSubjects = document.querySelectorAll('input[name="subject_ids[]"]:checked');
        const totalUnitsSpan = document.getElementById('totalUnits');
        const unitWarning = document.getElementById('unitWarning');
        const maxUnitsDisplay = document.getElementById('maxUnitsDisplay');
        const semester = document.getElementById('semester');
        
        if (!totalUnitsSpan || !unitWarning || !semester || !maxUnitsDisplay) return;
        
        const MAX_UNITS = semester.value === 'Summer' ? 9 : 24;
        maxUnitsDisplay.textContent = MAX_UNITS;
        
        let total = 0;
        checkedSubjects.forEach(checkbox => {
            total += parseInt(checkbox.dataset.units || 0);
        });
        
        totalUnitsSpan.textContent = total;
        
        if (total > MAX_UNITS) {
            unitWarning.classList.remove('d-none');
        } else {
            unitWarning.classList.add('d-none');
        }
    }

    function updateSubjectSelection(checkbox) {
        updateTotalUnits();
        updateSelectedCount();
    }
</script>
@endpush
@endsection
