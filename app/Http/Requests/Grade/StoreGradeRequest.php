<?php

namespace App\Http\Requests\Grade;

use App\Models\Grade;
use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Grade::isValidGrade($value)) {
                        $fail('The grade must be between 1.00 and 5.00 and increment by 0.25, or INC for incomplete.');
                    }
                },
            ],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required' => 'An enrollment must be selected.',
            'enrollment_id.exists' => 'The selected enrollment does not exist.',
            'subject_id.required' => 'A subject must be selected.',
            'subject_id.exists' => 'The selected subject does not exist.',
            'grade.required' => 'The grade is required.',
        ];
    }
} 