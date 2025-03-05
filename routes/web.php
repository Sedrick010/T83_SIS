<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('auth.login');
});

// Basic auth routes
Route::middleware('auth')->group(function () {
    // Simple dashboard redirect
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Admin routes - temporarily without role middleware
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Student Management
        Route::resource('students', StudentController::class);
        
        // Subject Management
        Route::resource('subjects', SubjectController::class);
        
        // Enrollment Management
        Route::resource('enrollments', EnrollmentController::class);
        
        // Grade Management
        Route::resource('grades', GradeController::class);
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
