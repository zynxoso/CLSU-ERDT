<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ScholarUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check for admin permissions or if scholar is updating their own profile
        return $this->user() &&
               ($this->user()->role === 'admin' ||
               ($this->user()->role === 'scholar' &&
                $this->user()->scholarProfile &&
                $this->route('id') == $this->user()->scholarProfile->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentYear = Carbon::now()->year;
        $scholarId = $this->route('id');

        return [
            // Personal information
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(
                    optional(\App\Models\ScholarProfile::find($scholarId))->user_id
                )
            ],
            'contact_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+?\d{1,3}[\s-]?)?(\(?\d{1,4}\)?[\s-]?)?\d{5,11}$/'
            ],
            'address' => ['required', 'string', 'min:5', 'max:500'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other,Prefer not to say'],
            'birth_date' => [
                'nullable',
                'date',
                'before:'.Carbon::now()->subYears(16)->format('Y-m-d'),
                'after:'.Carbon::now()->subYears(100)->format('Y-m-d'),
            ],

            // Academic information
            'university' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'status' => [
                'required',
                'string',
                'in:New,Ongoing,On Extension,Graduated,Terminated'
            ],
            'start_date' => [
                'required',
                'date',
                'after:'.Carbon::now()->subYears(10)->format('Y-m-d'),
                'before:'.Carbon::now()->addYears(1)->format('Y-m-d'),
            ],
            'expected_completion_date' => [
                'required',
                'date',
                'after:start_date',
                'before:'.Carbon::now()->addYears(10)->format('Y-m-d'),
            ],
            'actual_completion_date' => [
                'nullable',
                'date',
                'after:start_date',
                'before:'.Carbon::now()->addYears(1)->format('Y-m-d'),
            ],

            // Previous education
            'bachelor_degree' => ['nullable', 'string', 'max:255'],
            'bachelor_university' => ['nullable', 'string', 'max:255'],

            // Research information
            'research_area' => ['nullable', 'string', 'max:255'],
            'research_title' => ['nullable', 'string', 'max:255'],
            'research_abstract' => ['nullable', 'string', 'max:5000'],
            'notes' => ['nullable', 'string', 'max:1000'],

            // Location information (optional)
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:255'],

            // Additional information
            'scholar_id' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('scholar_profiles', 'scholar_id')->ignore($scholarId)
            ],
            'degree_program' => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'integer', 'min:1', 'max:10'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
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
            'email' => 'email address',
            'contact_number' => 'phone number',
            'bachelor_degree' => 'bachelor\'s degree',
            'bachelor_university' => 'bachelor\'s university',
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
            'first_name.regex' => 'The first name may only contain letters, spaces, hyphens, and apostrophes.',
            'middle_name.regex' => 'The middle name may only contain letters, spaces, hyphens, and apostrophes.',
            'last_name.regex' => 'The last name may only contain letters, spaces, hyphens, and apostrophes.',
            'contact_number.regex' => 'The phone number format is invalid.',
            'birth_date.before' => 'The scholar must be at least 16 years old.',
            'birth_date.after' => 'The birth date must be within the last 100 years.',
            'start_date.after' => 'The start date cannot be more than 10 years in the past.',
            'start_date.before' => 'The start date cannot be more than 1 year in the future.',
            'expected_completion_date.after' => 'The expected completion date must be after the start date.',
            'expected_completion_date.before' => 'The expected completion date cannot be more than 10 years in the future.',
            'actual_completion_date.after' => 'The actual completion date must be after the start date.',
            'actual_completion_date.before' => 'The actual completion date cannot be more than 1 year in the future.',
        ];
    }
}
