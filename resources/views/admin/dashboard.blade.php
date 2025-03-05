<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Total Students</h3>
                        <p class="text-3xl font-bold">{{ $totalStudents }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Total Subjects</h3>
                        <p class="text-3xl font-bold">{{ $totalSubjects }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Total Enrollments</h3>
                        <p class="text-3xl font-bold">{{ $totalEnrollments }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.subjects.index') }}" class="block p-6 bg-blue-100 rounded-lg hover:bg-blue-200">
                            <h4 class="font-semibold">Manage Subjects</h4>
                            <p class="text-sm">Add, edit, or remove subjects</p>
                        </a>
                        <a href="{{ route('admin.enrollments.index') }}" class="block p-6 bg-green-100 rounded-lg hover:bg-green-200">
                            <h4 class="font-semibold">Manage Enrollments</h4>
                            <p class="text-sm">Handle student enrollments</p>
                        </a>
                        <a href="{{ route('admin.grades.index') }}" class="block p-6 bg-yellow-100 rounded-lg hover:bg-yellow-200">
                            <h4 class="font-semibold">Manage Grades</h4>
                            <p class="text-sm">View and update student grades</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 