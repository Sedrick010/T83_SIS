<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Grades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Grade Report</h3>
                    @if($grades->isEmpty())
                        <p>No grades available yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 text-left">Subject Code</th>
                                        <th class="px-4 py-2 text-left">Subject Name</th>
                                        <th class="px-4 py-2 text-center">Grade</th>
                                        <th class="px-4 py-2 text-left">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grades as $grade)
                                        <tr class="border-b">
                                            <td class="px-4 py-2">{{ $grade->subject->code }}</td>
                                            <td class="px-4 py-2">{{ $grade->subject->name }}</td>
                                            <td class="px-4 py-2 text-center">{{ $grade->grade }}</td>
                                            <td class="px-4 py-2">{{ $grade->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 