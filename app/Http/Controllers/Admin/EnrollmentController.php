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
    public function index()
    {
        $enrollments = Enrollment::with(['user', 'subjects'])
            ->whereHas('user', function($query) {
                $query->whereNotNull('role'); // Only show enrollments for active students
            })
            ->latest()
            ->paginate(10);

        return view('admin.enrollments.index', compact('enrollments'));
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
            'user_id' => 'required|exists:users,id',
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

        try {
            // Check for existing enrollment in the same semester
            $enrollment = Enrollment::where('user_id', $request->user_id)
                ->where('academic_year', $request->academic_year)
                ->where('semester', $request->semester)
                ->first();

            if ($enrollment) {
                // Check for duplicate subjects
                $duplicateSubjects = $enrollment->subjects()
                    ->whereIn('subjects.id', $request->subject_ids)
                    ->pluck('code')
                    ->join(', ');

                if ($duplicateSubjects) {
                    return back()
                        ->withInput()
                        ->with('error', "Student is already enrolled in the following subjects: $duplicateSubjects");
                }
            } else {
                // Create new enrollment
                $enrollment = Enrollment::create([
                    'user_id' => $request->user_id,
                    'academic_year' => $request->academic_year,
                    'semester' => $request->semester,
                    'status' => $request->status,
                    'notes' => $request->notes,
                ]);
            }

            // Attach subjects
            $enrollment->subjects()->attach($request->subject_ids, [
                'status' => $request->status,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()
                ->route('admin.enrollments.index')
                ->with('success', count($request->subject_ids) . ' subjects enrolled successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error creating enrollment: ' . $e->getMessage());
        }
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user', 'subjects', 'grades']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        $enrollment->load('subjects');

        // Get available subjects (excluding already enrolled ones)
        $enrolledSubjectIds = $enrollment->subjects->pluck('id')->toArray();
        $availableSubjects = Subject::whereNotIn('id', $enrolledSubjectIds)
            ->orderBy('code')
            ->get();

        return view('admin.enrollments.edit', compact('enrollment', 'availableSubjects'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
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

        // Update enrollment details
        $enrollment->update([
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        // Sync subjects with their status
        $syncData = array_fill_keys($request->subject_ids, [
            'status' => $request->status,
            'updated_at' => now()
        ]);
        $enrollment->subjects()->sync($syncData);

        return redirect()
            ->route('admin.enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    public function destroy(Enrollment $enrollment)
    {
        // Check if enrollment has grades
        if ($enrollment->grades()->exists()) {
            return back()->with('error', 'Cannot delete enrollment with existing grades.');
        }

        // Detach all subjects before deleting the enrollment
        $enrollment->subjects()->detach();
        $enrollment->delete();

        return redirect()
            ->route('admin.enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }

    public function history(Request $request)
    {
        $query = Enrollment::with(['user', 'user.student', 'subjects'])
            ->withTrashed() // Include soft deleted records
            ->when($request->filled('search'), function($q) use ($request) {
                $search = $request->search;
                $q->whereHas('user', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('student', function($q) use ($search) {
                            $q->where('student_id_number', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('academic_year'), function($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            })
            ->when($request->filled('semester'), function($q) use ($request) {
                $q->where('semester', $request->semester);
            })
            ->when($request->filled('status'), function($q) use ($request) {
                if ($request->status === 'deleted') {
                    $q->whereNotNull('deleted_at');
                } else {
                    $q->whereNull('deleted_at');
                }
            });

        // Get unique academic years for the filter dropdown
        $academicYears = Enrollment::withTrashed()
            ->select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        $enrollments = $query->latest()->paginate(10);

        return view('admin.enrollments.history', compact('enrollments', 'academicYears'));
    }

    public function restore(Enrollment $enrollment)
    {
        // Check if the enrollment has grades
        if ($enrollment->grades()->exists()) {
            return back()->with('error', 'Cannot restore enrollment with existing grades.');
        }

        $enrollment->restore();
        $enrollment->update(['status' => 'enrolled']);

        return back()->with('success', 'Enrollment restored successfully.');
    }
}
