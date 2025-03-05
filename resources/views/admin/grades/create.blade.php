<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Grade') }}
            </h2>
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

                    <form method="POST" action="{{ route('admin.grades.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Enrollment Selection -->
                            <div>
                                <x-input-label for="enrollment_id" :value="__('Select Student and Subject')" />
                                <select id="enrollment_id" name="enrollment_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Enrollment</option>
                                    @foreach($enrollments->groupBy('user.name') as $studentName => $studentEnrollments)
                                        <optgroup label="{{ $studentName }}">
                                            @foreach($studentEnrollments as $enrollment)
                                                <option value="{{ $enrollment->id }}">
                                                    {{ $enrollment->subject->code }} - {{ $enrollment->subject->name }}
                                                    ({{ $enrollment->academic_year }}, {{ $enrollment->semester }} Semester)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('enrollment_id')" class="mt-2" />
                            </div>

                            <!-- Midterm Grade -->
                            <div>
                                <x-input-label for="midterm" :value="__('Midterm Grade')" />
                                <x-text-input id="midterm" name="midterm" type="number" step="0.25" min="1.00" max="5.00" class="mt-1 block w-full" :value="old('midterm')" required />
                                <p class="mt-1 text-sm text-gray-500">Enter grade between 1.00 and 5.00 (in increments of 0.25)</p>
                                <x-input-error :messages="$errors->get('midterm')" class="mt-2" />
                            </div>

                            <!-- Finals Grade -->
                            <div>
                                <x-input-label for="finals" :value="__('Finals Grade')" />
                                <x-text-input id="finals" name="finals" type="number" step="0.25" min="1.00" max="5.00" class="mt-1 block w-full" :value="old('finals')" required />
                                <p class="mt-1 text-sm text-gray-500">Enter grade between 1.00 and 5.00 (in increments of 0.25)</p>
                                <x-input-error :messages="$errors->get('finals')" class="mt-2" />
                            </div>

                            <!-- Remarks -->
                            <div>
                                <x-input-label for="remarks" :value="__('Remarks')" />
                                <textarea id="remarks" name="remarks" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('remarks') }}</textarea>
                                <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Create Grade') }}</x-primary-button>
                            <a href="{{ route('admin.grades.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const midtermInput = document.getElementById('midterm');
            const finalsInput = document.getElementById('finals');
            
            function updateGrades() {
                const midterm = parseFloat(midtermInput.value) || 0;
                const finals = parseFloat(finalsInput.value) || 0;
                
                if (midterm && finals) {
                    document.getElementById('remarks').value = 
                        `Midterm Grade: ${midterm.toFixed(2)}\n` +
                        `Finals Grade: ${finals.toFixed(2)}`;
                }
            }

            midtermInput.addEventListener('input', updateGrades);
            finalsInput.addEventListener('input', updateGrades);
        });
    </script>
    @endpush
</x-app-layout> 