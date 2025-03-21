@extends('layouts.pageTemplate')
@section('title', 'Dashboard')
@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-symbols-rounded opacity-10">group</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Students</p>
                    <h4 class="mb-0">{{ $totalStudents }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-symbols-rounded opacity-10">menu_book</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Subjects</p>
                    <h4 class="mb-0">{{ $totalSubjects }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-symbols-rounded opacity-10">how_to_reg</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Enrollments</p>
                    <h4 class="mb-0">{{ $totalEnrollments }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Quick Links</h6>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="row px-4">
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('admin.subjects.index') }}" class="card h-100">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4">
                                    <i class="material-symbols-rounded opacity-10">library_books</i>
                                </div>
                                <div class="pt-1">
                                    <h6 class="mb-0">Manage Subjects</h6>
                                    <p class="text-sm mb-0">Add, edit, or remove subjects</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('admin.enrollments.index') }}" class="card h-100">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4">
                                    <i class="material-symbols-rounded opacity-10">school</i>
                                </div>
                                <div class="pt-1">
                                    <h6 class="mb-0">Manage Enrollments</h6>
                                    <p class="text-sm mb-0">Handle student enrollments</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('admin.grades.index') }}" class="card h-100">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4">
                                    <i class="material-symbols-rounded opacity-10">grade</i>
                                </div>
                                <div class="pt-1">
                                    <h6 class="mb-0">Manage Grades</h6>
                                    <p class="text-sm mb-0">View and update student grades</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 