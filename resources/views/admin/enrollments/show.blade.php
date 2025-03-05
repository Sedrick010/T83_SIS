<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Enrollment Details') }}
            </h2>
            <a href="{{ route('admin.enrollments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Student Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-gray-600">Name:</span>
                                    <p class="mt-1">{{ $enrollment->user->name }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Student Number:</span>
                                    <p class="mt-1">{{ $enrollment->user->student_number }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Course:</span>
                                    <p class="mt-1">{{ $enrollment->user->course }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Year Level:</span>
                                    <p class="mt-1">{{ $enrollment->user->year_level }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Email:</span>
                                    <p class="mt-1">{{ $enrollment->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subject Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Subject Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-gray-600">Subject Code:</span>
                                    <p class="mt-1">{{ $enrollment->subject->code }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Subject Name:</span>
                                    <p class="mt-1">{{ $enrollment->subject->name }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Units:</span>
                                    <p class="mt-1">{{ $enrollment->subject->units }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Description:</span>
                                    <p class="mt-1">{{ $enrollment->subject->description ?: 'No description available.' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Enrollment Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Enrollment Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <span class="text-gray-600">Academic Year:</span>
                                    <p class="mt-1">{{ $enrollment->academic_year }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Semester:</span>
                                    <p class="mt-1">{{ $enrollment->semester }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Status:</span>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 rounded text-white {{ $enrollment->status === 'active' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Enrolled On:</span>
                                    <p class="mt-1">{{ $enrollment->created_at->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Last Updated:</span>
                                    <p class="mt-1">{{ $enrollment->updated_at->format('F d, Y') }}</p>
                                </div>
                                @if($enrollment->notes)
                                    <div class="md:col-span-3">
                                        <span class="text-gray-600">Notes:</span>
                                        <p class="mt-1">{{ $enrollment->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Grade Information -->
                        @if($enrollment->grade)
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Grade Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <span class="text-gray-600">Grade:</span>
                                        <p class="mt-1">
                                            <span class="px-2 py-1 rounded text-white {{ $enrollment->grade->grade >= 75 ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ $enrollment->grade->grade }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Status:</span>
                                        <p class="mt-1">
                                            @if($enrollment->grade->grade >= 75)
                                                <span class="text-green-600 font-semibold">PASSED</span>
                                            @else
                                                <span class="text-red-600 font-semibold">FAILED</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($enrollment->grade->remarks)
                                        <div class="md:col-span-3">
                                            <span class="text-gray-600">Remarks:</span>
                                            <p class="mt-1">{{ $enrollment->grade->remarks }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.enrollments.edit', $enrollment) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Edit Enrollment') }}
                        </a>
                        @if(!$enrollment->grade)
                            <a href="{{ route('admin.grades.create', ['enrollment_id' => $enrollment->id]) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Add Grade') }}
                            </a>
                        @endif
                        <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this enrollment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete Enrollment') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 