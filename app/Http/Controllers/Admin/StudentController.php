<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\StudentRegistrationNotification;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')
            ->latest()
            ->paginate(10);
            
        // Sync student numbers from students table if they're missing in users table
        foreach ($students as $student) {
            if (empty($student->student_number) && $student->student) {
                $student->update(['student_number' => $student->student->student_id_number]);
            }
        }
            
        return view('admin.students.index', compact('students'));
    }

    public function registrations()
    {
        $registrations = User::where('role', 'student')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
            
        return view('admin.students.registrations', compact('registrations'));
    }

    public function approve(Request $request, User $user)
    {
        $validated = $request->validate([
            'student_number' => ['required', 'string', 'max:255', 'unique:users,student_number'],
            'course' => ['required', 'string', 'in:BSIT,BSCS,BSIS'],
            'year_level' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        $user->update([
            'student_number' => $validated['student_number'],
            'course' => $validated['course'],
            'year_level' => $validated['year_level'],
            'status' => 'approved'
        ]);

        return redirect()->route('admin.students.registrations')
            ->with('success', 'Student registration approved successfully.');
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);

        return redirect()->route('admin.students.registrations')
            ->with('success', 'Student registration rejected.');
    }

    public function create()
    {
        // Get users who are not already students
        $nonStudentUsers = User::whereDoesntHave('student')->get();
        
        return view('admin.students.create', compact('nonStudentUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:students,user_id',
            'student_id_number' => 'required|unique:students,student_id_number',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'contact_number' => 'required|string',
            'course' => 'required|in:BSIT,BSCS,BSIS',
            'year_level' => 'required|integer|between:1,4',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1st,2nd,Summer',
        ]);

        try {
            DB::beginTransaction();

            // Create the student record
            $student = Student::create($request->all());

            // Get the user
            $user = User::findOrFail($request->user_id);

            // Update user's role to student and set student_number
            $user->update([
                'role' => User::ROLE_STUDENT,
                'student_number' => $request->student_id_number,
                'course' => $request->course,
                'year_level' => $request->year_level
            ]);

            // Send notification to the user
            $notificationData = [
                'student_id_number' => $request->student_id_number,
                'course' => $request->course,
                'year_level' => $request->year_level,
            ];
            $user->notify(new StudentRegistrationNotification($notificationData));

            // Check if the registered user is the currently logged-in user
            if (auth()->id() === $user->id) {
                // Store success message in flash session
                $message = 'Your account has been registered as a student. You can now access student features.';
                session()->flash('success', $message);
                
                // Mark the notification as read since the user is seeing it immediately
                $user->unreadNotifications->where('type', StudentRegistrationNotification::class)->markAsRead();
                
                $redirectUrl = route('student.dashboard');
            } else {
                $message = 'Student registered successfully. A notification has been sent to their account.';
                session()->flash('success', $message);
                $redirectUrl = route('admin.students.index');
            }

            DB::commit();

            // Return a JSON response if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl);
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error registering student: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->with('error', 'Error registering student: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(User $student)
    {
        // Sync student number from students table if it's missing in users table
        if (empty($student->student_number) && $student->student) {
            $student->update(['student_number' => $student->student->student_id_number]);
            $student->refresh();
        }
        
        return view('admin.students.show', compact('student'));
    }

    public function edit(User $student)
    {
        // Sync student number from students table if it's missing in users table
        if (empty($student->student_number) && $student->student) {
            $student->update(['student_number' => $student->student->student_id_number]);
            $student->refresh();
        }
        
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $student->id],
            'student_number' => ['required', 'string', 'max:255', 'unique:users,student_number,' . $student->id],
            'course' => ['required', 'string'],
            'year_level' => ['required', 'integer', 'min:1', 'max:4'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $student->update($validated);

        if ($request->filled('password')) {
            $student->update([
                'password' => bcrypt($request->password)
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        try {
            DB::beginTransaction();

            // First, soft delete all enrollments
            $student->enrollments()->update(['status' => 'inactive']);
            $student->enrollments()->delete();

            // Then, delete the student record
            if ($student->student) {
                $student->student->delete();
            }

            // Finally update the user record
            $student->update([
                'role' => null
            ]);

            DB::commit();

            return redirect()->route('admin.students.index')
                ->with('success', 'Student removed successfully. All associated enrollments have been archived.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error removing student: ' . $e->getMessage());
        }
    }
} 