<?php

namespace App\Http\Requests\Grade;

use App\Models\Grade;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
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
            'grade.required' => 'The grade is required.',
        ];
    }
} 