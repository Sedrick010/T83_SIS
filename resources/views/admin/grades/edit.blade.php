<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Grade') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.grades.update', $grade) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student (Read-only) -->
                            <div>
                                <x-input-label for="student_name" :value="__('Student')" />
                                <x-text-input id="student_name" type="text" class="mt-1 block w-full bg-gray-100" value="{{ $grade->enrollment->user->name }}" disabled />
                            </div>

                            <!-- Subject (Read-only) -->
                            <div>
                                <x-input-label for="subject_name" :value="__('Subject')" />
                                <x-text-input id="subject_name" type="text" class="mt-1 block w-full bg-gray-100" value="{{ $grade->enrollment->subject->code }} - {{ $grade->enrollment->subject->name }}" disabled />
                            </div>

                            <!-- Grade -->
                            <div>
                                <x-input-label for="grade" :value="__('Grade')" />
                                <x-text-input id="grade" name="grade" type="number" step="0.01" min="0" max="100" class="mt-1 block w-full" :value="old('grade', $grade->grade)" required />
                                <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                            </div>

                            <!-- Academic Year -->
                            <div>
                                <x-input-label for="academic_year" :value="__('Academic Year')" />
                                <select id="academic_year" name="academic_year" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @php
                                        $currentYear = date('Y');
                                        for($i = 0; $i < 5; $i++) {
                                            $academicYear = ($currentYear - $i) . '-' . ($currentYear - $i + 1);
                                            $selected = old('academic_year', $grade->enrollment->academic_year) == $academicYear ? 'selected' : '';
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
                                    <option value="First" {{ old('semester', $grade->enrollment->semester) == 'First' ? 'selected' : '' }}>First</option>
                                    <option value="Second" {{ old('semester', $grade->enrollment->semester) == 'Second' ? 'selected' : '' }}>Second</option>
                                    <option value="Summer" {{ old('semester', $grade->enrollment->semester) == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>

                            <!-- Remarks -->
                            <div class="md:col-span-2">
                                <x-input-label for="remarks" :value="__('Remarks')" />
                                <textarea id="remarks" name="remarks" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('remarks', $grade->remarks) }}</textarea>
                                <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Update Grade') }}</x-primary-button>
                            <a href="{{ route('admin.grades.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 