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
        $currentEnrollments = $user->enrollments()
            ->with(['subjects', 'grades'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();
        
        // Set a fixed GPA value instead of calculating it
        $gpa = 0;

        // Get recent grades
        $recentGrades = $user->enrollments()
            ->with(['subjects', 'grades'])
            ->whereHas('grades')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->flatMap(function ($enrollment) {
                return $enrollment->grades;
            })
            ->sortByDesc('created_at')
            ->take(5);

        $currentAcademicYear = '2023-2024'; // You may want to make this dynamic

        return view('student.dashboard', [
            'user' => $user,
            'currentEnrollments' => $currentEnrollments,
            'gpa' => $gpa,
            'currentAcademicYear' => $currentAcademicYear,
            'recentGrades' => $recentGrades
        ]);
    }

    public function grades(): View
    {
        $user = auth()->user();
        $enrollments = $user->enrollments()
            ->with(['subjects', 'grades'])
            ->get();
        
        // Group enrollments by academic year and semester
        $gradesBySemester = $enrollments
            ->groupBy(['academic_year', 'semester'])
            ->map(function ($yearGroup) {
                return $yearGroup->map(function ($semesterGroup) {
                    return [
                        'academic_year' => $semesterGroup->first()->academic_year,
                        'semester' => $semesterGroup->first()->semester,
                        'enrollments' => $semesterGroup,
                        'gpa' => 0 // Fixed GPA value
                    ];
                });
            })
            ->sortByDesc(function ($yearGroup, $year) {
                return $year;
            });

        return view('student.grades', [
            'user' => $user,
            'gradesBySemester' => $gradesBySemester
        ]);
    }

    public function profile(): View
    {
        $user = auth()->user();
        return view('student.profile', [
            'user' => $user
        ]);
    }

    public function enrollments(): View
    {
        $user = auth()->user();
        
        // Get current enrollments
        $currentEnrollments = $user->enrollments()
            ->with(['subjects', 'grades'])
            ->where('academic_year', '2023-2024')
            ->where('semester', '2nd')
            ->get();

        // Get enrollment history (all previous enrollments)
        $enrollmentHistory = $user->enrollments()
            ->with(['subjects', 'grades'])
            ->where(function($query) {
                $query->where('academic_year', '<', '2023-2024')
                    ->orWhere(function($q) {
                        $q->where('academic_year', '2023-2024')
                            ->where('semester', '<', '2nd');
                    });
            })
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get()
            ->groupBy('academic_year')
            ->map(function ($yearGroup) {
                return $yearGroup->groupBy('semester')
                    ->map(function ($semesterGroup) {
                        return [
                            'academic_year' => $semesterGroup->first()->academic_year,
                            'semester' => $semesterGroup->first()->semester,
                            'enrollments' => $semesterGroup,
                            'total_units' => $semesterGroup->sum(function ($enrollment) {
                                return $enrollment->subjects->sum('units');
                            }),
                            'gpa' => 0 // Fixed GPA value
                        ];
                    });
            });

        return view('student.enrollments', [
            'user' => $user,
            'currentEnrollments' => $currentEnrollments,
            'enrollmentHistory' => $enrollmentHistory,
            'currentAcademicYear' => '2023-2024',
            'currentSemester' => '2nd'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'student_number' => ['required', 'string', 'max:255', 'unique:users,student_number,' . $user->id],
            'course' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
