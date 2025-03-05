<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Enrollments') }}
            </h2>
            <a href="{{ route('admin.enrollments.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Enrollment
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

                    <!-- Filters -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="student_filter" class="block text-sm font-medium text-gray-700">Student</label>
                            <select id="student_filter" name="student" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Students</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="academic_year_filter" class="block text-sm font-medium text-gray-700">Academic Year</label>
                            <select id="academic_year_filter" name="academic_year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Years</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="semester_filter" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select id="semester_filter" name="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Semesters</option>
                                <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st</option>
                                <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd</option>
                                <option value="Summer" {{ request('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Student</th>
                                    <th class="px-4 py-2 text-left">Subject</th>
                                    <th class="px-4 py-2 text-left">Academic Year</th>
                                    <th class="px-4 py-2 text-left">Semester</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $groupedEnrollments = $enrollments->groupBy(function($enrollment) {
                                        return $enrollment->user_id . '-' . $enrollment->academic_year . '-' . $enrollment->semester;
                                    });
                                @endphp

                                @forelse($groupedEnrollments as $groupKey => $group)
                                    @php
                                        $firstEnrollment = $group->first();
                                        $rowspan = $group->count();
                                    @endphp
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2" rowspan="{{ $rowspan }}">{{ $firstEnrollment->id }}</td>
                                        <td class="px-4 py-2" rowspan="{{ $rowspan }}">
                                            {{ $firstEnrollment->user->name }}
                                            <div class="text-sm text-gray-500">{{ $firstEnrollment->user->student_number }}</div>
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $firstEnrollment->subject->code }} - {{ $firstEnrollment->subject->name }}
                                            <div class="text-sm text-gray-500">{{ $firstEnrollment->subject->units }} units</div>
                                        </td>
                                        <td class="px-4 py-2" rowspan="{{ $rowspan }}">{{ $firstEnrollment->academic_year }}</td>
                                        <td class="px-4 py-2" rowspan="{{ $rowspan }}">{{ $firstEnrollment->semester }}</td>
                                        <td class="px-4 py-2" rowspan="{{ $rowspan }}">
                                            <span class="px-2 py-1 rounded text-white {{ $firstEnrollment->status === 'enrolled' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                                {{ ucfirst($firstEnrollment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center" rowspan="{{ $rowspan }}">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.enrollments.show', $firstEnrollment) }}" 
                                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.enrollments.edit', $firstEnrollment) }}" 
                                                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.enrollments.destroy', $firstEnrollment) }}" 
                                                      method="POST" 
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded"
                                                            onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($group->skip(1) as $enrollment)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-2">
                                                {{ $enrollment->subject->code }} - {{ $enrollment->subject->name }}
                                                <div class="text-sm text-gray-500">{{ $enrollment->subject->units }} units</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-2 text-center">No enrollments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $enrollments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filters = ['student_filter', 'academic_year_filter', 'semester_filter'];
            filters.forEach(filterId => {
                document.getElementById(filterId).addEventListener('change', function() {
                    const params = new URLSearchParams(window.location.search);
                    params.set(this.name, this.value);
                    window.location.search = params.toString();
                });
            });
        });
    </script>
</x-app-layout> 