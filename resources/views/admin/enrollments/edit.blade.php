<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Enrollment') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student Information (Read-only) -->
                            <div>
                                <x-input-label for="student_name" :value="__('Student')" />
                                <x-text-input id="student_name" type="text" class="mt-1 block w-full bg-gray-100" 
                                    value="{{ $enrollment->user->name }} ({{ $enrollment->user->student_number }})" disabled />
                                <input type="hidden" name="student_id" value="{{ $enrollment->user_id }}" />
                            </div>

                            <!-- Academic Year -->
                            <div>
                                <x-input-label for="academic_year" :value="__('Academic Year')" />
                                <select id="academic_year" name="academic_year" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @php
                                        $currentYear = date('Y');
                                        for($i = 0; $i < 5; $i++) {
                                            $academicYear = ($currentYear - $i) . '-' . ($currentYear - $i + 1);
                                            $selected = old('academic_year', $enrollment->academic_year) == $academicYear ? 'selected' : '';
                                            echo '<option value="' . $academicYear . '" ' . $selected . '>' . $academicYear . '</option>';
                                        }
                                    @endphp
                                </select>
                                <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                            </div>

                            <!-- Semester -->
                            <div>
                                <x-input-label for="semester" :value="__('Semester')" />
                                <select id="semester" name="semester" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="1st" {{ old('semester', $enrollment->semester) == '1st' ? 'selected' : '' }}>1st</option>
                                    <option value="2nd" {{ old('semester', $enrollment->semester) == '2nd' ? 'selected' : '' }}>2nd</option>
                                    <option value="Summer" {{ old('semester', $enrollment->semester) == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="enrolled" {{ old('status', $enrollment->status) == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                    <option value="dropped" {{ old('status', $enrollment->status) == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                    <option value="completed" {{ old('status', $enrollment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Current Subjects -->
                            <div class="md:col-span-2">
                                <x-input-label :value="__('Current Subjects')" />
                                <div class="mt-2 space-y-2">
                                    @php
                                        $currentSubjects = $enrollments->pluck('subject');
                                        $totalUnits = $currentSubjects->sum('units');
                                    @endphp
                                    @foreach($currentSubjects as $subject)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                            <div>
                                                <span class="font-medium">{{ $subject->code }} - {{ $subject->name }}</span>
                                                <span class="text-sm text-gray-500 ml-2">({{ $subject->units }} units)</span>
                                            </div>
                                            <button type="button" 
                                                    onclick="removeSubject(this, '{{ $subject->id }}')"
                                                    class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            <input type="hidden" name="subject_ids[]" value="{{ $subject->id }}">
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-sm text-gray-600">Total Units: <span id="totalUnits">{{ $totalUnits }}</span></p>
                            </div>

                            <!-- Add New Subjects -->
                            <div class="md:col-span-2">
                                <x-input-label for="new_subjects" :value="__('Add New Subjects')" />
                                <select id="new_subjects" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select a subject to add</option>
                                    @foreach($availableSubjects as $subject)
                                        <option value="{{ $subject->id }}" 
                                                data-code="{{ $subject->code }}"
                                                data-name="{{ $subject->name }}"
                                                data-units="{{ $subject->units }}">
                                            {{ $subject->code }} - {{ $subject->name }} ({{ $subject->units }} units)
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" 
                                        onclick="addSubject()"
                                        class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Add Subject
                                </button>
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $enrollment->notes) }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Update Enrollment') }}</x-primary-button>
                            <a href="{{ route('admin.enrollments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addSubject() {
            const select = document.getElementById('new_subjects');
            const option = select.options[select.selectedIndex];
            
            if (!option.value) return;

            const subjectId = option.value;
            const code = option.dataset.code;
            const name = option.dataset.name;
            const units = parseInt(option.dataset.units);

            // Check if subject is already added
            if (document.querySelector(`input[value="${subjectId}"]`)) {
                alert('This subject is already added to the enrollment.');
                return;
            }

            // Calculate new total units
            const currentTotal = parseInt(document.getElementById('totalUnits').textContent);
            const newTotal = currentTotal + units;
            const maxUnits = document.getElementById('semester').value === 'Summer' ? 9 : 24;

            if (newTotal > maxUnits) {
                alert(`Adding this subject would exceed the maximum units allowed (${maxUnits}) for this semester.`);
                return;
            }

            // Create new subject element
            const subjectDiv = document.createElement('div');
            subjectDiv.className = 'flex items-center justify-between p-3 bg-gray-50 rounded';
            subjectDiv.innerHTML = `
                <div>
                    <span class="font-medium">${code} - ${name}</span>
                    <span class="text-sm text-gray-500 ml-2">(${units} units)</span>
                </div>
                <button type="button" 
                        onclick="removeSubject(this, '${subjectId}')"
                        class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <input type="hidden" name="subject_ids[]" value="${subjectId}">
            `;

            document.querySelector('.space-y-2').appendChild(subjectDiv);
            document.getElementById('totalUnits').textContent = newTotal;
            select.value = '';
        }

        function removeSubject(button, subjectId) {
            const subjectDiv = button.closest('div');
            const units = parseInt(subjectDiv.querySelector('.text-gray-500').textContent.match(/\d+/)[0]);
            const currentTotal = parseInt(document.getElementById('totalUnits').textContent);
            
            document.getElementById('totalUnits').textContent = currentTotal - units;
            subjectDiv.remove();
        }

        // Update max units when semester changes
        document.getElementById('semester').addEventListener('change', function() {
            const maxUnits = this.value === 'Summer' ? 9 : 24;
            const currentTotal = parseInt(document.getElementById('totalUnits').textContent);
            
            if (currentTotal > maxUnits) {
                alert(`Warning: Current total units (${currentTotal}) exceed the maximum allowed (${maxUnits}) for this semester.`);
            }
        });
    </script>
</x-app-layout> 