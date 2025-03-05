<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Your Enrollments</h3>
                    @if($enrollments->isEmpty())
                        <p>You are not enrolled in any subjects yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($enrollments as $enrollment)
                                <div class="p-4 border rounded-lg">
                                    <h4 class="font-semibold">{{ $enrollment->subject->code }}</h4>
                                    <p class="text-sm">{{ $enrollment->subject->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $enrollment->academic_year }} - {{ $enrollment->semester }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('student.grades') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                View My Grades
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 