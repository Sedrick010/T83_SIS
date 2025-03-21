<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        // Add student-specific rules if the field is present in the request
        if ($this->has('student_number')) {
            $rules['student_number'] = [
                'required', 
                'string', 
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id)
            ];
            $rules['course'] = ['required', 'string'];
            $rules['year_level'] = ['required', 'integer', 'min:1', 'max:4'];
        }

        return $rules;
    }
}
