<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id', Rule::exists('users', 'id')->where('role', 'student')],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_year' => ['required', 'string', 'regex:/^\d{4}-\d{4}$/'],
            'semester' => ['required', 'in:1st,2nd,Summer'],
            'status' => ['required', 'in:enrolled,dropped,completed'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'The selected student does not exist.',
            'subject_id.exists' => 'The selected subject does not exist.',
            'academic_year.regex' => 'The academic year must be in the format YYYY-YYYY.',
        ];
    }
} 