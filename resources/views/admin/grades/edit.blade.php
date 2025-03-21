@extends('layouts.pageTemplate')
@section('title', 'Edit Grade')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Edit Grade</h6>
                </div>
            </div>
            <div class="card-body">
                <!-- Student & Subject Info -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-plain">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-xl position-relative">
                                            <div class="bg-gradient-primary shadow-primary border-radius-lg d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                <i class="material-symbols-rounded text-white" style="font-size: 24px;">person</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto my-auto">
                                        <div class="h-100">
                                            <h5 class="mb-1">{{ $grade->enrollment->user->name }}</h5>
                                            @php
                                                $subject = $grade->enrollment->subjects->where('id', $grade->subject_id)->first();
                                            @endphp
                                            <p class="mb-0 font-weight-normal text-sm">
                                                {{ $subject->code }} - {{ $subject->name }}
                                            </p>
                                            <p class="mb-0 font-weight-normal text-sm text-secondary">
                                                {{ $grade->enrollment->academic_year }}, {{ $grade->enrollment->semester }} Semester
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.grades.update', $grade) }}" class="row g-3">
                    @csrf
                    @method('PATCH')

                    <!-- Grade -->
                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="grade">Grade</label>
                            <select class="form-control" id="grade" name="grade" required>
                                <option value="">Select Grade</option>
                                <option value="1.00" {{ old('grade', $grade->grade) == '1.00' ? 'selected' : '' }}>1.00 - Excellent</option>
                                <option value="1.25" {{ old('grade', $grade->grade) == '1.25' ? 'selected' : '' }}>1.25 - Excellent</option>
                                <option value="1.50" {{ old('grade', $grade->grade) == '1.50' ? 'selected' : '' }}>1.50 - Very Good</option>
                                <option value="1.75" {{ old('grade', $grade->grade) == '1.75' ? 'selected' : '' }}>1.75 - Very Good</option>
                                <option value="2.00" {{ old('grade', $grade->grade) == '2.00' ? 'selected' : '' }}>2.00 - Good</option>
                                <option value="2.25" {{ old('grade', $grade->grade) == '2.25' ? 'selected' : '' }}>2.25 - Good</option>
                                <option value="2.50" {{ old('grade', $grade->grade) == '2.50' ? 'selected' : '' }}>2.50 - Fair</option>
                                <option value="2.75" {{ old('grade', $grade->grade) == '2.75' ? 'selected' : '' }}>2.75 - Fair</option>
                                <option value="3.00" {{ old('grade', $grade->grade) == '3.00' ? 'selected' : '' }}>3.00 - Passed</option>
                                <option value="3.25" {{ old('grade', $grade->grade) == '3.25' ? 'selected' : '' }}>3.25 - Failed</option>
                                <option value="3.50" {{ old('grade', $grade->grade) == '3.50' ? 'selected' : '' }}>3.50 - Failed</option>
                                <option value="3.75" {{ old('grade', $grade->grade) == '3.75' ? 'selected' : '' }}>3.75 - Failed</option>
                                <option value="4.00" {{ old('grade', $grade->grade) == '4.00' ? 'selected' : '' }}>4.00 - Failed</option>
                                <option value="4.25" {{ old('grade', $grade->grade) == '4.25' ? 'selected' : '' }}>4.25 - Failed</option>
                                <option value="4.50" {{ old('grade', $grade->grade) == '4.50' ? 'selected' : '' }}>4.50 - Failed</option>
                                <option value="4.75" {{ old('grade', $grade->grade) == '4.75' ? 'selected' : '' }}>4.75 - Failed</option>
                                <option value="5.00" {{ old('grade', $grade->grade) == '5.00' ? 'selected' : '' }}>5.00 - Failed</option>
                                <option value="INC" {{ old('grade', $grade->grade) == 'INC' ? 'selected' : '' }}>INC - Incomplete</option>
                            </select>
                            @error('grade')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Select grade (1.00-5.00). Passing grade is 3.00 or lower.</small>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="col-md-12">
                        <div class="input-group input-group-static mb-4">
                            <label for="remarks">Remarks (Optional)</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ old('remarks', $grade->remarks) }}</textarea>
                            @error('remarks')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn bg-gradient-primary">Update Grade</button>
                        <a href="{{ route('admin.grades.index') }}" class="btn bg-gradient-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 