<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Details') }}
            </h2>
            <a href="{{ route('admin.students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-gray-600">Student Number:</span>
                                    <p class="mt-1">{{ $student->student_number }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Name:</span>
                                    <p class="mt-1">{{ $student->name }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Email:</span>
                                    <p class="mt-1">{{ $student->email }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Course:</span>
                                    <p class="mt-1">{{ $student->course }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Year Level:</span>
                                    <p class="mt-1">{{ $student->year_level }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Status:</span>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 rounded text-white {{ $student->status === 'active' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Enrollment History</h3>
                            <div class="mt-4">
                                @if($student->enrollments->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full table-auto">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="px-4 py-2 text-left">Subject</th>
                                                    <th class="px-4 py-2 text-left">Academic Year</th>
                                                    <th class="px-4 py-2 text-left">Semester</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->enrollments as $enrollment)
                                                    <tr class="border-b">
                                                        <td class="px-4 py-2">{{ $enrollment->subject->name }}</td>
                                                        <td class="px-4 py-2">{{ $enrollment->academic_year }}</td>
                                                        <td class="px-4 py-2">{{ $enrollment->semester }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-gray-500">No enrollment history found.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.students.edit', $student) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Edit Student') }}
                        </a>
                        <form action="{{ route('admin.students.destroy', $student) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this student?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete Student') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 