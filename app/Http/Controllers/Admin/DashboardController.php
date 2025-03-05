<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalSubjects = Subject::count();
        $totalEnrollments = Enrollment::count();

        return view('admin.dashboard', compact('totalStudents', 'totalSubjects', 'totalEnrollments'));
    }
}
