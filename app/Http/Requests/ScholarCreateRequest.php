<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ScholarCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Log authorization attempt
        $logPath = storage_path('logs/scholar_auth.log');
        $logMessage = "Scholar creation authorization check at " . now() . "\n";

        // Ensure log directory exists
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }

        // Check if there's a logged-in user
        if (!$this->user()) {
            $logMessage .= "UNAUTHORIZED: No authenticated user\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);
            return false;
        }

        // Log user info
        $logMessage .= "User ID: " . $this->user()->id . "\n";
        $logMessage .= "User Email: " . $this->user()->email . "\n";
        $logMessage .= "User Role: " . $this->user()->role . "\n";

        // Only admin users can create scholars
        $isAuthorized = $this->user() && $this->user()->role === 'admin';
        $logMessage .= "Authorization result: " . ($isAuthorized ? "AUTHORIZED" : "UNAUTHORIZED") . "\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        return $isAuthorized;
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
            // User information
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],

            // Personal information
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
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
                'before:'.Carbon::now()->subYears(16)->format('Y-m-d'), // Must be at least 16 years old
                'after:'.Carbon::now()->subYears(100)->format('Y-m-d'), // Reasonable age limit
            ],

            // Academic information
            'university' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'status' => [
                'required',
                'string',
                'in:New,Ongoing,On Extension,Graduated,Terminated,Deferred Repayment'
            ],
            'start_date' => [
                'required',
                'date',
                'after:'.Carbon::now()->subYears(10)->format('Y-m-d'), // Reasonable start date
                'before:'.Carbon::now()->addYears(1)->format('Y-m-d'), // Can't be too far in the future
            ],
            'expected_completion_date' => [
                'required',
                'date',
                'after:start_date',
                'before:'.Carbon::now()->addYears(10)->format('Y-m-d'), // Reasonable completion date limit
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
            'bachelor_graduation_year' => [
                'nullable',
                'integer',
                'min:1950',
                'max:'.$currentYear
            ],

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
            'scholar_id' => ['nullable', 'string', 'max:50', 'unique:scholar_profiles,scholar_id'],
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
            'contact_number' => 'phone number',
            'bachelor_degree' => 'bachelor\'s degree',
            'bachelor_university' => 'bachelor\'s university',
            'bachelor_graduation_year' => 'graduation year',
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
            'email.regex' => 'The email address format is invalid.',
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

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Log validation errors
        $logPath = storage_path('logs/scholar_validation.log');
        $logMessage = "Scholar creation validation failed at " . now() . "\n";

        // Ensure log directory exists
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }

        // Log user info if authenticated
        if ($this->user()) {
            $logMessage .= "User ID: " . $this->user()->id . "\n";
            $logMessage .= "User Email: " . $this->user()->email . "\n";
            $logMessage .= "User Role: " . $this->user()->role . "\n";
        } else {
            $logMessage .= "User: Not authenticated\n";
        }

        // Log the validation errors
        $logMessage .= "Validation Errors: " . json_encode($validator->errors()->toArray()) . "\n";

        // Log the submitted data
        $logMessage .= "Submitted Data: " . json_encode($this->except(['password', '_token'])) . "\n";

        file_put_contents($logPath, $logMessage, FILE_APPEND);

        // Call the parent method to continue the normal validation failure process
        parent::failedValidation($validator);
    }
}
