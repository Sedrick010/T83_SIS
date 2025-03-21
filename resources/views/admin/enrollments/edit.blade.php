@extends('layouts.pageTemplate')
@section('title', 'Edit Enrollment')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Edit Enrollment</h6>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible text-white" role="alert">
                        <span class="text-sm">{{ session('success') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible text-white" role="alert">
                        <span class="text-sm">{{ session('error') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}" class="row g-3">
                    @csrf
                    @method('PUT')

                    <!-- Student Information (Read-only) -->
                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="student_name">Student</label>
                            <input type="text" class="form-control" id="student_name" 
                                value="{{ $enrollment->user->name }} ({{ $enrollment->user->student_number }})" 
                                disabled>
                            <input type="hidden" name="student_id" value="{{ $enrollment->user_id }}">
                        </div>
                    </div>

                    <!-- Academic Year -->
                    <div class="col-md-3">
                        <div class="input-group input-group-static mb-4">
                            <label for="academic_year">Academic Year</label>
                            <select class="form-control" id="academic_year" name="academic_year" required>
                                @php
                                    $currentYear = date('Y');
                                    for($i = 0; $i < 5; $i++) {
                                        $academicYear = ($currentYear - $i) . '-' . ($currentYear - $i + 1);
                                        $selected = old('academic_year', $enrollment->academic_year) == $academicYear ? 'selected' : '';
                                        echo '<option value="' . $academicYear . '" ' . $selected . '>' . $academicYear . '</option>';
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
                                <option value="1st" {{ old('semester', $enrollment->semester) == '1st' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd" {{ old('semester', $enrollment->semester) == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                                <option value="Summer" {{ old('semester', $enrollment->semester) == 'Summer' ? 'selected' : '' }}>Summer</option>
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

                    <!-- Currently Selected Subjects -->
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header p-3">
                                <h6 class="mb-0">Currently Selected Subjects</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row" id="selectedSubjectsDisplay">
                                    <!-- This will be populated by JavaScript -->
                                </div>
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
                                    @foreach($availableSubjects as $subject)
                                        @php
                                            $hasGrade = $enrollment->grades->contains('subject_id', $subject->id);
                                            $isEnrolled = $enrollment->subjects->contains('id', $subject->id);
                                        @endphp
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-3 position-relative {{ $hasGrade ? 'bg-gray-100' : '' }}">
                                                <div class="form-check">
                                                    <input class="form-check-input subject-checkbox" 
                                                           type="checkbox" 
                                                           name="subject_ids[]"
                                                           value="{{ $subject->id }}"
                                                           id="subject_{{ $subject->id }}"
                                                           data-code="{{ $subject->code }}"
                                                           data-name="{{ $subject->name }}"
                                                           data-units="{{ $subject->units }}"
                                                           data-has-grade="{{ $hasGrade ? 'true' : 'false' }}"
                                                           {{ $hasGrade || $isEnrolled ? 'checked' : '' }}
                                                           {{ $hasGrade ? 'disabled' : '' }}
                                                           onchange="updateSubjectSelection(this)">
                                                    <label class="form-check-label" for="subject_{{ $subject->id }}">
                                                        <h6 class="mb-1 text-sm">{{ $subject->code }}</h6>
                                                        <p class="mb-1 text-xs text-secondary">{{ $subject->name }}</p>
                                                        <small class="text-xs text-info">Units: {{ $subject->units }}</small>
                                                        @if($hasGrade)
                                                            <span class="badge bg-gradient-success">Graded</span>
                                                        @endif
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
                                <option value="enrolled" {{ old('status', $enrollment->status) == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="dropped" {{ old('status', $enrollment->status) == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                <option value="completed" {{ old('status', $enrollment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
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
                            <textarea class="form-control" id="notes" name="notes" rows="4">{{ old('notes', $enrollment->notes) }}</textarea>
                            @error('notes')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn bg-gradient-primary">Update Enrollment</button>
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
        // First, ensure all checkboxes have the correct data attributes
        document.querySelectorAll('input[name="subject_ids[]"]').forEach(checkbox => {
            const label = checkbox.closest('.form-check').querySelector('.form-check-label');
            if (label) {
                checkbox.dataset.code = label.querySelector('h6').textContent;
                checkbox.dataset.name = label.querySelector('p').textContent;
                checkbox.dataset.units = label.querySelector('small').textContent.replace('Units: ', '');
            }
        });

        // Initialize total units and selected count
        updateTotalUnits();
        updateSelectedCount();
        updateSelectedSubjectsDisplay();
        
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
        const enrollmentForm = document.querySelector('form');
        if (enrollmentForm) {
            enrollmentForm.addEventListener('submit', function(e) {
                const checkedSubjects = document.querySelectorAll('input[name="subject_ids[]"]:checked');
                if (checkedSubjects.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one subject for enrollment.');
                    return false;
                }

                // Check if any subject with grades is being removed
                const uncheckedWithGrades = document.querySelectorAll('input[name="subject_ids[]"]:not(:checked)[data-has-grade="true"]');
                if (uncheckedWithGrades.length > 0) {
                    e.preventDefault();
                    alert('Cannot remove subjects that already have grades assigned.');
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
            const units = parseInt(checkbox.dataset.units) || 0;
            total += units;
        });
        
        totalUnitsSpan.textContent = total;
        
        if (total > MAX_UNITS) {
            unitWarning.classList.remove('d-none');
        } else {
            unitWarning.classList.add('d-none');
        }
    }

    function updateSelectedSubjectsDisplay() {
        const selectedSubjectsContainer = document.getElementById('selectedSubjectsDisplay');
        const checkedSubjects = document.querySelectorAll('input[name="subject_ids[]"]:checked');
        
        if (!selectedSubjectsContainer) return;
        
        selectedSubjectsContainer.innerHTML = '';
        
        if (checkedSubjects.length === 0) {
            selectedSubjectsContainer.innerHTML = '<div class="col-12"><p class="text-muted mb-0">No subjects selected</p></div>';
            return;
        }

        checkedSubjects.forEach(checkbox => {
            const hasGrade = checkbox.dataset.hasGrade === 'true';
            const card = document.createElement('div');
            card.className = 'col-md-4 mb-3';
            card.innerHTML = `
                <div class="border rounded p-3 position-relative ${hasGrade ? 'bg-gray-100' : ''}">
                    ${!hasGrade ? `
                        <button type="button" 
                                class="btn btn-link text-danger p-0 position-absolute top-0 end-0 mt-2 me-2"
                                onclick="unselectSubject('${checkbox.id}')">
                            <i class="material-symbols-rounded">close</i>
                        </button>
                    ` : ''}
                    <h6 class="mb-1 text-sm">${checkbox.dataset.code}</h6>
                    <p class="mb-1 text-xs text-secondary">${checkbox.dataset.name}</p>
                    <small class="text-xs text-info">Units: ${checkbox.dataset.units}</small>
                    ${hasGrade ? '<span class="badge bg-gradient-success ms-2">Graded</span>' : ''}
                </div>
            `;
            selectedSubjectsContainer.appendChild(card);
        });
    }

    function unselectSubject(checkboxId) {
        const checkbox = document.getElementById(checkboxId);
        if (checkbox && checkbox.dataset.hasGrade !== 'true') {
            checkbox.checked = false;
            updateSubjectSelection(checkbox);
        }
    }

    function updateSubjectSelection(checkbox) {
        if (checkbox.dataset.hasGrade === 'true' && !checkbox.checked) {
            checkbox.checked = true;
            alert('Cannot unselect subjects that already have grades assigned.');
            return;
        }
        updateTotalUnits();
        updateSelectedCount();
        updateSelectedSubjectsDisplay();
    }
</script>
@endpush
@endsection 