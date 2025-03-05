<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Enrollment') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.enrollments.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student Selection -->
                            <div>
                                <x-input-label for="student_id" :value="__('Student')" />
                                <select id="student_id" name="student_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->student_number }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                            </div>

                            <!-- Subject Selection -->
                            <div class="md:col-span-2">
                                <x-input-label for="subject_ids" :value="__('Subjects')" />
                                <div class="mt-1 p-4 border rounded-md">
                                    <div class="mb-2 text-sm text-gray-600">
                                        Selected Units: <span id="totalUnits">0</span>
                                        <span id="unitWarning" class="hidden ml-2 text-red-600">
                                            Warning: Exceeds recommended units per semester (24)
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($subjects as $subject)
                                            <div class="flex items-start space-x-2">
                                                <input type="checkbox" 
                                                    name="subject_ids[]" 
                                                    id="subject_{{ $subject->id }}"
                                                    value="{{ $subject->id }}"
                                                    class="subject-checkbox mt-1"
                                                    data-units="{{ $subject->units }}"
                                                    {{ in_array($subject->id, old('subject_ids', [])) ? 'checked' : '' }}>
                                                <label for="subject_{{ $subject->id }}" class="text-sm">
                                                    <div class="font-medium">{{ $subject->code }}</div>
                                                    <div class="text-gray-600">{{ $subject->name }}</div>
                                                    <div class="text-gray-500">Units: {{ $subject->units }}</div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />
                            </div>

                            <!-- Academic Year -->
                            <div>
                                <x-input-label for="academic_year" :value="__('Academic Year')" />
                                <select id="academic_year" name="academic_year" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Academic Year</option>
                                    @php
                                        $currentYear = date('Y');
                                        for($i = 0; $i < 5; $i++) {
                                            $academicYear = ($currentYear - $i) . '-' . ($currentYear - $i + 1);
                                            echo '<option value="' . $academicYear . '" ' . (old('academic_year') == $academicYear ? 'selected' : '') . '>' . $academicYear . '</option>';
                                        }
                                    @endphp
                                </select>
                                <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                            </div>

                            <!-- Semester -->
                            <div>
                                <x-input-label for="semester" :value="__('Semester')" />
                                <select id="semester" name="semester" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Semester</option>
                                    <option value="1st" {{ old('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd" {{ old('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="enrolled" {{ old('status', 'enrolled') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                    <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Create Enrollment') }}</x-primary-button>
                            <a href="{{ route('admin.enrollments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
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
            const checkboxes = document.querySelectorAll('.subject-checkbox');
            const totalUnitsSpan = document.getElementById('totalUnits');
            const unitWarning = document.getElementById('unitWarning');
            const MAX_UNITS = 24;

            function updateTotalUnits() {
                let total = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        total += parseInt(checkbox.dataset.units);
                    }
                });
                totalUnitsSpan.textContent = total;
                
                if (total > MAX_UNITS) {
                    unitWarning.classList.remove('hidden');
                } else {
                    unitWarning.classList.add('hidden');
                }
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalUnits);
            });

            // Initial calculation
            updateTotalUnits();
        });
    </script>
    @endpush
</x-app-layout> 