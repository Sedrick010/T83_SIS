@extends('layouts.pageTemplate')
@section('title', 'My Profile')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Profile Information</h6>
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

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card card-profile">
                            <div class="position-relative">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg p-3">
                                    <div class="avatar avatar-xxl position-relative">
                                        <div class="bg-gradient-info shadow-info border-radius-lg d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                            <i class="material-symbols-rounded text-white" style="font-size: 40px;">admin_panel_settings</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center mt-4">
                                <h4 class="mb-0">{{ Auth::user()->name }}</h4>
                                <p class="text-sm text-secondary mb-2">Administrator</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
                                    @csrf
                                    @method('patch')

                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                            @error('name')
                                                <span class="text-danger text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                            @error('email')
                                                <span class="text-danger text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        @error('password')
                                            <span class="text-danger text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn bg-gradient-primary">Update Profile</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection 