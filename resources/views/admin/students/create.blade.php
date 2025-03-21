@extends('layouts.pageTemplate')
@section('title', 'Add New Student')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Add New Student</h6>
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

                <form method="POST" action="{{ route('admin.students.store') }}" class="row g-3" id="studentForm">
                    @csrf

                    <!-- User Selection -->
                    <div class="col-md-12">
                        <div class="input-group input-group-static mb-4">
                            <label for="user_id" class="ms-0">Select User</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">Choose a user</option>
                                @foreach($nonStudentUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="col-md-4">
                        <div class="input-group input-group-static mb-4">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group input-group-static mb-4">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group input-group-static mb-4">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                            @error('middle_name')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Student ID and Course -->
                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="student_id_number">Student ID Number</label>
                            <input type="text" class="form-control" id="student_id_number" name="student_id_number" value="{{ old('student_id_number') }}" required>
                            @error('student_id_number')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="course">Course</label>
                            <select class="form-control" id="course" name="course" required>
                                <option value="">Select Course</option>
                                <option value="BSIT" {{ old('course') == 'BSIT' ? 'selected' : '' }}>BSIT</option>
                                <option value="BSCS" {{ old('course') == 'BSCS' ? 'selected' : '' }}>BSCS</option>
                                <option value="BSIS" {{ old('course') == 'BSIS' ? 'selected' : '' }}>BSIS</option>
                            </select>
                            @error('course')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="birth_date">Birth Date</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                            @error('birth_date')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-md-12">
                        <div class="input-group input-group-static mb-4">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                            @error('contact_number')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="col-md-4">
                        <div class="input-group input-group-static mb-4">
                            <label for="year_level">Year Level</label>
                            <select class="form-control" id="year_level" name="year_level" required>
                                <option value="">Select Year Level</option>
                                @for($i = 1; $i <= 4; $i++)
                                    @php
                                        $suffix = match($i) {
                                            1 => 'st',
                                            2 => 'nd',
                                            3 => 'rd',
                                            default => 'th'
                                        };
                                    @endphp
                                    <option value="{{ $i }}" {{ old('year_level') == $i ? 'selected' : '' }}>{{ $i }}{{ $suffix }} Year</option>
                                @endfor
                            </select>
                            @error('year_level')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group input-group-static mb-4">
                            <label for="academic_year">Academic Year</label>
                            <input type="text" class="form-control" id="academic_year" name="academic_year" value="{{ old('academic_year', date('Y').'-'.(date('Y')+1)) }}" required>
                            @error('academic_year')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
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

                    <div class="col-12">
                        <button type="submit" class="btn bg-gradient-primary">Register as Student</button>
                        <a href="{{ route('admin.students.index') }}" class="btn bg-gradient-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Registration Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="material-symbols-rounded text-success" style="font-size: 48px;">check_circle</i>
                    <p id="successMessage" class="mt-3"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="redirectButton" class="btn bg-gradient-primary">Go to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('studentForm');
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button to prevent double submission
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update modal content
                    document.getElementById('successMessage').textContent = data.message;
                    document.getElementById('redirectButton').href = data.redirect;
                    
                    // Show success modal
                    successModal.show();
                    
                    // Auto redirect after 3 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 3000);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                // Re-enable submit button
                submitButton.disabled = false;
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger alert-dismissible text-white';
                errorDiv.innerHTML = `
                    <span class="text-sm">${error.message || 'An error occurred during registration.'}</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                `;
                form.insertBefore(errorDiv, form.firstChild);
            });
        });

        // Handle input focus for outline inputs
        const inputs = document.querySelectorAll('.input-group-outline .form-control');
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
    });
</script>
@endpush 