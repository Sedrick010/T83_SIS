<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Subject Details') }}
            </h2>
            <a href="{{ route('admin.subjects.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                            <h3 class="text-lg font-medium text-gray-900">Subject Information</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-gray-600">Subject Code:</span>
                                    <p class="mt-1">{{ $subject->code }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Name:</span>
                                    <p class="mt-1">{{ $subject->name }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Units:</span>
                                    <p class="mt-1">{{ $subject->units }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Description:</span>
                                    <p class="mt-1">{{ $subject->description ?: 'No description available.' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Enrollment Information</h3>
                            <div class="mt-4">
                                <div class="mb-4">
                                    <span class="text-gray-600">Total Enrollments:</span>
                                    <p class="mt-1">{{ $subject->enrollments->count() }}</p>
                                </div>

                                @if($subject->enrollments->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full table-auto">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="px-4 py-2 text-left">Student</th>
                                                    <th class="px-4 py-2 text-left">Academic Year</th>
                                                    <th class="px-4 py-2 text-left">Semester</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($subject->enrollments as $enrollment)
                                                    <tr class="border-b">
                                                        <td class="px-4 py-2">{{ $enrollment->user->name }}</td>
                                                        <td class="px-4 py-2">{{ $enrollment->academic_year }}</td>
                                                        <td class="px-4 py-2">{{ $enrollment->semester }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-gray-500">No students currently enrolled in this subject.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.subjects.edit', $subject) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Edit Subject') }}
                        </a>
                        <form action="{{ route('admin.subjects.destroy', $subject) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this subject?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete Subject') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 