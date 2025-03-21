@extends('layouts.pageTemplate')

@section('title', 'Edit Student')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Edit Student</h6>
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
                
                <form method="POST" action="{{ route('admin.students.update', $student) }}" class="row g-3">
                    @csrf
                    @method('PUT')
                    
                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="student_number">Student Number</label>
                            <input type="text" class="form-control" name="student_number" value="{{ old('student_number', $student->student_number) }}" required>
                            @error('student_number')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $student->name) }}" required>
                            @error('name')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $student->email) }}" required>
                            @error('email')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="course">Course</label>
                            <input type="text" class="form-control" name="course" value="{{ old('course', $student->course) }}" required>
                            @error('course')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="year_level" class="ms-0">Year Level</label>
                            <select class="form-control" id="year_level" name="year_level" required>
                                @for ($i = 1; $i <= 4; $i++)
                                    @php
                                        $suffix = match($i) {
                                            1 => 'st',
                                            2 => 'nd',
                                            3 => 'rd',
                                            default => 'th'
                                        };
                                    @endphp
                                    <option value="{{ $i }}" {{ old('year_level', $student->year_level) == $i ? 'selected' : '' }}>{{ $i }}{{ $suffix }} Year</option>
                                @endfor
                            </select>
                            @error('year_level')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="status" class="ms-0">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <p class="text-secondary text-xs mt-1">Leave blank to keep current password</p>
                        @error('password')
                            <span class="text-danger text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn bg-gradient-primary">Update Student</button>
                        <a href="{{ route('admin.students.index') }}" class="btn bg-gradient-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush 