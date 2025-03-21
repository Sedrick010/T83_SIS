@extends('layouts.pageTemplate')
@section('title', 'Add New Subject')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Add New Subject</h6>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.subjects.store') }}" class="row g-3">
                    @csrf

                    <!-- Subject Code -->
                    <div class="col-md-4">
                        <div class="input-group input-group-static mb-4">
                            <label for="code">Subject Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required autofocus>
                            @error('code')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <div class="input-group input-group-static mb-4">
                            <label for="name">Subject Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Units -->
                    <div class="col-md-2">
                        <div class="input-group input-group-static mb-4">
                            <label for="units">Units</label>
                            <input type="number" class="form-control" id="units" name="units" min="1" max="6" value="{{ old('units') }}" required>
                            @error('units')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <div class="input-group input-group-static mb-4">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn bg-gradient-primary">Create Subject</button>
                        <a href="{{ route('admin.subjects.index') }}" class="btn bg-gradient-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 