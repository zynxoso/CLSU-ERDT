<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ScholarProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow scholars to update their own profiles
        return $this->user() && $this->user()->role === 'scholar';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentYear = Carbon::now()->year;

        return [
            // Personal information
            'birthdate' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],

            // Academic information
            'university' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],

            // Contact information
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+?\d{1,3}[\s-]?)?(\(?\d{1,4}\)?[\s-]?)?\d{5,11}$/'
            ],
            'address' => ['required', 'string', 'min:5', 'max:500'],

            // Academic dates
            'start_date' => [
                'nullable',
                'date',
                'after:'.Carbon::now()->subYears(10)->format('Y-m-d'),
                'before:'.Carbon::now()->addYears(1)->format('Y-m-d')
            ],
            'expected_completion_date' => [
                'nullable',
                'date',
                'after:'.($this->start_date ?? Carbon::now()->format('Y-m-d')),
                'before:'.Carbon::now()->addYears(10)->format('Y-m-d')
            ],

            // Optional academic information
            'major' => ['nullable', 'string', 'max:255'],
            'degree_level' => ['required', 'string', 'in:Master\'s,PhD'],
            'gpa' => ['nullable', 'numeric', 'min:1', 'max:5'],

            // Research information
            'research_title' => ['nullable', 'string', 'max:255'],
            'research_area' => ['nullable', 'string', 'max:255'],
            'research_abstract' => ['nullable', 'string', 'max:5000'],
            'advisor' => ['nullable', 'string', 'max:255'],

            // Profile photo
            'profile_photo' => ['nullable', 'image', 'max:2048'], // 2MB max
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'phone' => 'phone number',
            'gpa' => 'GPA',
            'degree_level' => 'degree level',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number format is invalid.',
            'start_date.after' => 'The start date cannot be more than 10 years in the past.',
            'start_date.before' => 'The start date cannot be more than 1 year in the future.',
            'expected_completion_date.after' => 'The expected completion date must be after the start date.',
            'expected_completion_date.before' => 'The expected completion date cannot be more than 10 years in the future.',
            'profile_photo.max' => 'The profile photo must not be larger than 2MB.',
        ];
    }
}
