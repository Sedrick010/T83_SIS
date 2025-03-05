<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $enrollments = $user->enrollments()->with('subject')->get();
        
        return view('student.dashboard', compact('enrollments'));
    }

    public function grades(): View
    {
        $user = auth()->user();
        $grades = $user->grades()->with(['subject', 'enrollment'])->get();
        
        return view('student.grades', compact('grades'));
    }
}
