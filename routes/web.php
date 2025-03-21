<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('auth.login');
});

// Basic auth routes
Route::middleware('auth')->group(function () {
    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Check if user is admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Check if user is a student
        if ($user->isStudent()) {
            if (session()->has('success')) {
                // If there's a success message, pass it to the student dashboard
                return redirect()->route('student.dashboard')->with('success', session('success'));
            }
            return redirect()->route('student.dashboard');
        }
        
        // If user is neither admin nor student, show a message
        return view('dashboard', [
            'message' => 'Your account is pending registration approval. Please contact the administrator.',
            'role' => $user->role
        ]);
    })->name('dashboard');

    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Resource routes
        Route::resource('students', StudentController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('enrollments', EnrollmentController::class);
        Route::get('enrollments-history', [EnrollmentController::class, 'history'])->name('enrollments.history');
        Route::patch('enrollments/{enrollment}/restore', [EnrollmentController::class, 'restore'])->name('enrollments.restore');
        Route::resource('grades', GradeController::class);
    });

    // Student routes
    Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/grades', [StudentDashboardController::class, 'grades'])->name('grades');
        Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
        Route::get('/enrollments', [StudentDashboardController::class, 'enrollments'])->name('enrollments');
        Route::patch('/profile', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/debug/subjects', function() {
    $active = App\Models\Subject::all()->toArray();
    $withTrashed = App\Models\Subject::withTrashed()->get()->toArray();
    dd([
        'active_subjects' => $active,
        'all_subjects_including_trashed' => $withTrashed
    ]);
});

require __DIR__.'/auth.php';
