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
        $grades = Grade::with(['enrollment.user', 'enrollment.subject'])
            ->latest()
            ->paginate(10);
        return view('admin.grades.index', compact('grades'));
    }

    public function create()
    {
        $enrollments = Enrollment::with(['user', 'subject'])
            ->whereDoesntHave('grades')
            ->where('status', 'enrolled')
            ->get()
            ->sortBy('user.name');

        return view('admin.grades.create', compact('enrollments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'midterm' => ['required', 'numeric', 'min:1.00', 'max:5.00'],
            'finals' => ['required', 'numeric', 'min:1.00', 'max:5.00'],
            'remarks' => ['nullable', 'string'],
        ]);

        try {
            if (Grade::where('enrollment_id', $request->enrollment_id)->exists()) {
                return back()->with('error', 'Grade already exists for this enrollment.')
                    ->withInput();
            }

            $enrollment = Enrollment::findOrFail($request->enrollment_id);
            
            $grade = Grade::create([
                'enrollment_id' => $request->enrollment_id,
                'midterm' => $request->midterm,
                'finals' => $request->finals,
                'remarks' => $request->remarks,
            ]);

            // Update enrollment status based on finals grade
            if ($request->finals <= 3.00) {
                $enrollment->update(['status' => 'completed']);
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
        $grade->load(['enrollment.user', 'enrollment.subject']);
        return view('admin.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $grade->load(['enrollment.user', 'enrollment.subject']);
        return view('admin.grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'midterm' => ['required', 'numeric', 'min:1.00', 'max:5.00'],
            'finals' => ['required', 'numeric', 'min:1.00', 'max:5.00'],
            'remarks' => ['nullable', 'string'],
        ]);

        try {
            $grade->update($validated);

            // Update enrollment status based on finals grade
            if ($grade->finals <= 3.00) {
                $grade->enrollment->update(['status' => 'completed']);
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
