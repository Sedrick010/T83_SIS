@extends('layouts.pageTemplate')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Welcome</h6>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="alert alert-info text-white" role="alert">
                    <h4 class="text-white">Account Status</h4>
                    <p class="mb-0">{{ $message }}</p>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h5>What's Next?</h5>
                        <ol class="mt-3">
                            <li>Wait for an administrator to register you as a student</li>
                            <li>Once registered, you'll be able to:
                                <ul class="mt-2">
                                    <li>View your student dashboard</li>
                                    <li>Check your enrollments</li>
                                    <li>View your grades</li>
                                    <li>And more...</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
