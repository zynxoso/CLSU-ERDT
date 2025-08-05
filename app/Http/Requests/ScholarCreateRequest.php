<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * @extends FormRequest
 */
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
        $user = Auth::user();
        if (!$user) {
            $logMessage .= "UNAUTHORIZED: No authenticated user\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);
            return false;
        }

        // Log user info
        $logMessage .= "User ID: " . $user->id . "\n";
        $logMessage .= "User Email: " . $user->email . "\n";
        $logMessage .= "User Role: " . $user->role . "\n";

        // Only admin users can create scholars
        $isAuthorized = $user && $user->role === 'admin';
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

            'gender' => ['nullable', 'string', 'in:Male,Female,Other,Prefer not to say'],
            'birth_date' => [
                'nullable',
                'date',
                'before:'.Carbon::now()->subYears(16)->format('Y-m-d'), // Must be at least 16 years old
                'after:'.Carbon::now()->subYears(100)->format('Y-m-d'), // Reasonable age limit
            ],

            // Academic information
            'intended_university' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],


            'enrollment_type' => ['required', 'string', 'in:New,Lateral'],
            'study_time' => ['required', 'string', 'in:Full-time,Part-time'],
            'scholarship_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'status' => [
                'required',
                'string',
                'in:Active,Graduated,Deferred,Dropped,Inactive'
            ],
            'start_date' => [
                'required',
                'date',
                'after:' . Carbon::now()->subYears(10)->format('Y-m-d'),
                'before:' . Carbon::now()->addYear()->format('Y-m-d')
            ],
            'actual_completion_date' => [
                'nullable',
                'date',
                'before:'.Carbon::now()->addYears(8)->format('Y-m-d'),
            ],

            'notes' => ['nullable', 'string', 'max:1000'],

            // Location information (optional)
            'street' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
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
            'contact_number' => 'phone number'
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

            'actual_completion_date.after' => 'The actual completion date must be after the start date.',
            'actual_completion_date.before' => 'The actual completion date cannot be more than 8 years in the future.',
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
        $user = Auth::user();
        if ($user) {
            $logMessage .= "User ID: " . $user->id . "\n";
            $logMessage .= "User Email: " . $user->email . "\n";
            $logMessage .= "User Role: " . $user->role . "\n";
        } else {
            $logMessage .= "User: Not authenticated\n";
        }

        // Log the validation errors
        $logMessage .= "Validation Errors: " . json_encode($validator->errors()->toArray()) . "\n";

        // Log the submitted data
        $submittedData = request()->all();
        unset($submittedData['password'], $submittedData['_token']);
        $logMessage .= "Submitted Data: " . json_encode($submittedData) . "\n";

        file_put_contents($logPath, $logMessage, FILE_APPEND);

        // Call the parent method to continue the normal validation failure process
        parent::failedValidation($validator);
    }
}
