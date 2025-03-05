<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Grades') }}
            </h2>
            <a href="{{ route('admin.grades.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Grade
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Student</th>
                                    <th class="px-4 py-2 text-left">Subject</th>
                                    <th class="px-4 py-2 text-left">Grade</th>
                                    <th class="px-4 py-2 text-left">Academic Year</th>
                                    <th class="px-4 py-2 text-left">Semester</th>
                                    <th class="px-4 py-2 text-left">Remarks</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades as $grade)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $grade->id }}</td>
                                        <td class="px-4 py-2">{{ $grade->enrollment->user->name }}</td>
                                        <td class="px-4 py-2">{{ $grade->enrollment->subject->name }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-white {{ $grade->getGradeColor() }}">
                                                Midterm: {{ number_format($grade->midterm, 2) }}
                                                <br>
                                                Finals: {{ number_format($grade->finals, 2) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ $grade->enrollment->academic_year }}</td>
                                        <td class="px-4 py-2">{{ $grade->enrollment->semester }}</td>
                                        <td class="px-4 py-2">
                                            <span class="font-semibold {{ $grade->getRemarks() === 'PASSED' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $grade->getRemarks() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.grades.show', $grade) }}" 
                                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.grades.edit', $grade) }}" 
                                                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.grades.destroy', $grade) }}" 
                                                      method="POST" 
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded"
                                                            onclick="return confirm('Are you sure you want to delete this grade?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-2 text-center">No grades found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $grades->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 