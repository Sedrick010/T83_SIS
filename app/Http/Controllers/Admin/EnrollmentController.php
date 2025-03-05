<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Enrollment\StoreEnrollmentRequest;
use App\Http\Requests\Enrollment\UpdateEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['user', 'subject'])
            ->orderBy('user_id')
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester');

        // Apply filters
        if ($request->filled('student')) {
            $query->where('user_id', $request->student);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $enrollments = $query->paginate(10);

        // Get all students for the filter dropdown
        $students = User::where('role', 'student')->orderBy('name')->get();

        // Get unique academic years for the filter dropdown
        $academicYears = Enrollment::distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        return view('admin.enrollments.index', compact('enrollments', 'students', 'academicYears'));
    }

    public function create()
    {
        $students = User::where('role', 'student')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        $subjects = Subject::orderBy('code')->get();

        return view('admin.enrollments.create', compact('students', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1st,2nd,Summer',
            'status' => 'required|in:enrolled,dropped,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate total units
        $totalUnits = Subject::whereIn('id', $request->subject_ids)->sum('units');
        $maxUnits = $request->semester === 'Summer' ? 9 : 24;

        if ($totalUnits > $maxUnits) {
            return back()
                ->withInput()
                ->with('error', "Total units ($totalUnits) exceeds the maximum allowed units ($maxUnits) for this semester.");
        }

        // Check for existing enrollments
        $existingEnrollments = Enrollment::where('user_id', $request->student_id)
            ->where('academic_year', $request->academic_year)
            ->where('semester', $request->semester)
            ->whereIn('subject_id', $request->subject_ids)
            ->get();

        if ($existingEnrollments->isNotEmpty()) {
            $subjects = Subject::whereIn('id', $existingEnrollments->pluck('subject_id'))->pluck('code')->join(', ');
            return back()
                ->withInput()
                ->with('error', "Student is already enrolled in the following subjects: $subjects");
        }

        // Check prerequisites
        $subjects = Subject::whereIn('id', $request->subject_ids)->get();
        foreach ($subjects as $subject) {
            if ($subject->prerequisites()->exists()) {
                $completedPrereqs = Enrollment::where('user_id', $request->student_id)
                    ->whereIn('subject_id', $subject->prerequisites->pluck('id'))
                    ->where('status', 'completed')
                    ->exists();

                if (!$completedPrereqs) {
                    $prereqCodes = $subject->prerequisites->pluck('code')->join(', ');
                    return back()
                        ->withInput()
                        ->with('error', "Prerequisites not met for {$subject->code}. Required: $prereqCodes");
                }
            }
        }

        // Create enrollments
        $enrollments = [];
        foreach ($request->subject_ids as $subjectId) {
            $enrollments[] = Enrollment::create([
                'user_id' => $request->student_id,
                'subject_id' => $subjectId,
                'academic_year' => $request->academic_year,
                'semester' => $request->semester,
                'status' => $request->status ?? 'enrolled',
                'notes' => $request->notes,
            ]);
        }

        return redirect()
            ->route('admin.enrollments.index')
            ->with('success', count($enrollments) . ' subjects enrolled successfully.');
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user', 'subject', 'grades']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        // Get all enrollments for this student in the same academic year and semester
        $enrollments = Enrollment::where('user_id', $enrollment->user_id)
            ->where('academic_year', $enrollment->academic_year)
            ->where('semester', $enrollment->semester)
            ->with('subject')
            ->get();

        // Get available subjects (excluding already enrolled ones)
        $enrolledSubjectIds = $enrollments->pluck('subject_id')->toArray();
        $availableSubjects = Subject::whereNotIn('id', $enrolledSubjectIds)
            ->orderBy('code')
            ->get();

        return view('admin.enrollments.edit', compact('enrollment', 'enrollments', 'availableSubjects'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1st,2nd,Summer',
            'status' => 'required|in:enrolled,dropped,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate total units for selected subjects
        $totalUnits = Subject::whereIn('id', $request->subject_ids)->sum('units');
        $maxUnits = $request->semester === 'Summer' ? 9 : 24;

        if ($totalUnits > $maxUnits) {
            return back()
                ->withInput()
                ->with('error', "Total units ($totalUnits) exceeds the maximum allowed units ($maxUnits) for this semester.");
        }

        // Get all current enrollments for this student in the same academic year and semester
        $currentEnrollments = Enrollment::where('user_id', $enrollment->user_id)
            ->where('academic_year', $enrollment->academic_year)
            ->where('semester', $enrollment->semester)
            ->get();

        // Get current subject IDs
        $currentSubjectIds = $currentEnrollments->pluck('subject_id')->toArray();

        // Determine which subjects to add and which to remove
        $subjectsToAdd = array_diff($request->subject_ids, $currentSubjectIds);
        $subjectsToRemove = array_diff($currentSubjectIds, $request->subject_ids);

        // Remove enrollments for removed subjects
        Enrollment::where('user_id', $enrollment->user_id)
            ->where('academic_year', $enrollment->academic_year)
            ->where('semester', $enrollment->semester)
            ->whereIn('subject_id', $subjectsToRemove)
            ->delete();

        // Update existing enrollments
        $updateData = [
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'status' => $request->status,
        ];
        
        if ($request->filled('notes')) {
            $updateData['notes'] = $request->notes;
        }

        Enrollment::where('user_id', $enrollment->user_id)
            ->where('academic_year', $enrollment->academic_year)
            ->where('semester', $enrollment->semester)
            ->update($updateData);

        // Add new enrollments for added subjects
        foreach ($subjectsToAdd as $subjectId) {
            $newEnrollment = [
                'user_id' => $request->student_id,
                'subject_id' => $subjectId,
                'academic_year' => $request->academic_year,
                'semester' => $request->semester,
                'status' => $request->status,
            ];
            
            if ($request->filled('notes')) {
                $newEnrollment['notes'] = $request->notes;
            }
            
            Enrollment::create($newEnrollment);
        }

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    public function destroy(Enrollment $enrollment)
    {
        // Check if enrollment has grades
        if ($enrollment->grades()->exists()) {
            return back()->with('error', 'Cannot delete enrollment with existing grades.');
        }

        $enrollment->delete();

        return redirect()
            ->route('admin.enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }
}
