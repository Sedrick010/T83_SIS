<?php

namespace App\Http\Requests\Grade;

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
            'midterm' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'finals' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'final_grade' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.exists' => 'The selected enrollment does not exist.',
            'midterm.min' => 'The midterm grade cannot be less than 0.',
            'midterm.max' => 'The midterm grade cannot be more than 100.',
            'finals.min' => 'The finals grade cannot be less than 0.',
            'finals.max' => 'The finals grade cannot be more than 100.',
            'final_grade.min' => 'The final grade cannot be less than 0.',
            'final_grade.max' => 'The final grade cannot be more than 100.',
        ];
    }
} 