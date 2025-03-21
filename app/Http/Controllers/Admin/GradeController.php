<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Grade\StoreGradeRequest;
use App\Http\Requests\Grade\UpdateGradeRequest;
use App\Models\Grade;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::with([
            'enrollment' => function($query) {
                $query->withTrashed(); // Include soft-deleted enrollments for reference
            },
            'enrollment.user' => function($query) {
                $query->whereNotNull('role'); // Only users who are still students
            },
            'enrollment.subjects' => function($query) {
                $query->withTrashed(); // Include soft-deleted subjects
            }
        ])
        ->whereHas('enrollment', function($query) {
            $query->whereNull('deleted_at') // Only active enrollments
                ->whereHas('user', function($q) {
                    $q->whereNotNull('role'); // Only active students
                });
        })
        ->latest()
        ->paginate(10);

        return view('admin.grades.index', compact('grades'));
    }

    public function create()
    {
        // Get all enrollments that have at least one subject without a grade
        $enrollments = Enrollment::with(['user', 'subjects', 'grades'])
            ->whereHas('user', function($query) {
                $query->whereNotNull('role'); // Only active students
            })
            ->where('status', 'enrolled')
            ->get()
            ->filter(function ($enrollment) {
                // Check if there are any subjects without grades
                return $enrollment->subjects->some(function ($subject) use ($enrollment) {
                    // Check if this subject doesn't have a grade yet
                    return !$enrollment->grades->where('subject_id', $subject->id)->count();
                });
            })
            ->sortBy('user.name');

        // Flatten enrollments to show each subject that doesn't have a grade yet
        $enrollmentSubjects = collect();
        foreach ($enrollments as $enrollment) {
            // Get subjects that don't have grades yet
            $subjectsWithoutGrades = $enrollment->subjects->filter(function ($subject) use ($enrollment) {
                return !$enrollment->grades->where('subject_id', $subject->id)->count();
            });

            foreach ($subjectsWithoutGrades as $subject) {
                $enrollmentSubjects->push([
                    'id' => $enrollment->id,
                    'subject_id' => $subject->id,
                    'student_name' => $enrollment->user->name,
                    'subject_name' => $subject->name,
                    'subject_code' => $subject->code
                ]);
            }
        }

        return view('admin.grades.create', [
            'enrollmentSubjects' => $enrollmentSubjects
        ]);
    }

    public function store(StoreGradeRequest $request)
    {
        try {
            if (Grade::where('enrollment_id', $request->enrollment_id)
                    ->where('subject_id', $request->subject_id)
                    ->exists()) {
                return back()->with('error', 'Grade already exists for this subject in this enrollment.')
                    ->withInput();
            }

            $enrollment = Enrollment::findOrFail($request->enrollment_id);
            
            // Verify that the subject belongs to the enrollment
            if (!$enrollment->subjects()->where('subjects.id', $request->subject_id)->exists()) {
                return back()->with('error', 'Selected subject is not part of this enrollment.')
                    ->withInput();
            }
            
            $grade = Grade::create([
                'enrollment_id' => $request->enrollment_id,
                'subject_id' => $request->subject_id,
                'grade' => $request->grade,
                'remarks' => $request->remarks,
            ]);

            // Update enrollment status if all subjects have passing grades
            $allSubjectsGraded = $enrollment->subjects()
                ->whereNotExists(function ($query) {
                    $query->from('grades')
                        ->whereColumn('grades.subject_id', 'subjects.id')
                        ->whereColumn('grades.enrollment_id', 'enrollment_subjects.enrollment_id');
                })
                ->doesntExist();

            if ($allSubjectsGraded) {
                $allPassing = Grade::where('enrollment_id', $enrollment->id)
                    ->where(function($query) {
                        $query->where('grade', '<=', 3.00)
                              ->whereNotIn('grade', ['INC']);
                    })
                    ->count() === $enrollment->subjects()->count();
                
                if ($allPassing) {
                    $enrollment->update(['status' => 'completed']);
                }
            }

            return redirect()->route('admin.grades.index')
                ->with('success', 'Grade added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding grade: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Grade $grade)
    {
        $grade->load(['enrollment.user', 'enrollment.subjects']);
        return view('admin.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $grade->load(['enrollment.user', 'enrollment.subjects']);
        return view('admin.grades.edit', compact('grade'));
    }

    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        try {
            $grade->update($request->validated());

            // Update enrollment status if all subjects have passing grades
            $enrollment = $grade->enrollment;
            $allSubjectsGraded = $enrollment->subjects()
                ->whereNotExists(function ($query) {
                    $query->from('grades')
                        ->whereColumn('grades.subject_id', 'subjects.id')
                        ->whereColumn('grades.enrollment_id', 'enrollment_subjects.enrollment_id');
                })
                ->doesntExist();

            if ($allSubjectsGraded) {
                $allPassing = Grade::where('enrollment_id', $enrollment->id)
                    ->where(function($query) {
                        $query->where('grade', '<=', 3.00)
                              ->whereNotIn('grade', ['INC']);
                    })
                    ->count() === $enrollment->subjects()->count();
                
                if ($allPassing) {
                    $enrollment->update(['status' => 'completed']);
                }
            }

            return redirect()->route('admin.grades.index')
                ->with('success', 'Grade updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating grade: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Grade $grade)
    {
        try {
            $grade->delete();
            return redirect()->route('admin.grades.index')
                ->with('success', 'Grade deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting grade: ' . $e->getMessage());
        }
    }
}
