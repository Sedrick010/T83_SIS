<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grade Details') }}
            </h2>
            <a href="{{ route('admin.grades.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                                    <p class="mt-1">{{ $grade->enrollment->user->name }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Student ID:</span>
                                    <p class="mt-1">{{ $grade->enrollment->user->student_id }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Email:</span>
                                    <p class="mt-1">{{ $grade->enrollment->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subject Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Subject Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-gray-600">Subject Code:</span>
                                    <p class="mt-1">{{ $grade->enrollment->subject->code }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Subject Name:</span>
                                    <p class="mt-1">{{ $grade->enrollment->subject->name }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Units:</span>
                                    <p class="mt-1">{{ $grade->enrollment->subject->units }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Grade Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Grade Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <span class="text-gray-600">Midterm:</span>
                                    <p class="mt-1">{{ number_format($grade->midterm, 2) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Finals:</span>
                                    <p class="mt-1">{{ number_format($grade->finals, 2) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Final Grade:</span>
                                    <p class="mt-1">
                                        <span class="px-4 py-2 rounded text-white {{ $grade->getGradeColor() }}">
                                            {{ number_format($grade->final_grade, 2) }}
                                            ({{ $grade->getLetterGrade() }})
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Status:</span>
                                    <p class="mt-1">
                                        <span class="font-semibold {{ $grade->getRemarks() === 'PASSED' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $grade->getRemarks() }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Date Recorded:</span>
                                    <p class="mt-1">{{ $grade->created_at->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Academic Year:</span>
                                    <p class="mt-1">{{ $grade->enrollment->academic_year }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Semester:</span>
                                    <p class="mt-1">{{ $grade->enrollment->semester }}</p>
                                </div>
                                @if($grade->remarks)
                                    <div class="md:col-span-3">
                                        <span class="text-gray-600">Remarks:</span>
                                        <p class="mt-1">{{ $grade->remarks }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.grades.edit', $grade) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Edit Grade') }}
                        </a>
                        <form action="{{ route('admin.grades.destroy', $grade) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this grade?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete Grade') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 