<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')
            ->latest()
            ->paginate(10);
        
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'student_number' => 'required|string|max:255|unique:users',
            'course' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:4',
            'password' => 'required|string|min:8',
        ]);

        $validated['role'] = 'student';
        $validated['password'] = bcrypt($validated['password']);
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(User $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(User $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'student_number' => 'required|string|max:255|unique:users,student_number,' . $student->id,
            'course' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:4',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        if ($student->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete student with existing enrollments.');
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
} 