<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(StoreSubjectRequest $request)
    {
        try {
            Subject::create($request->validated());
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating subject: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Subject $subject)
    {
        $subject->load(['enrollments.user' => function($query) {
            $query->where('role', 'student');
        }]);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        try {
            $subject->update($request->validated());
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating subject: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Subject $subject)
    {
        try {
            // Check if the subject has any enrollments through the pivot table
            if ($subject->enrollments()->exists()) {
                return back()->with('error', 'Cannot delete subject with enrolled students.');
            }
            
            $subject->delete();
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting subject: ' . $e->getMessage());
        }
    }
}
