<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScholarProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow scholars to update their own profiles
        return Auth::check() && Auth::user()->role === 'scholar';
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
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'suffix' => ['nullable', 'string', 'in:Jr.,Sr.,II,III,IV,V'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],

            // Academic information
            'intended_university' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            // Program field removed - using department instead
            'course_completed' => ['nullable', 'string', 'in:MS in Agricultural Engineering,BS Agricultural and Biosystem Engineering'],
            'university_graduated' => ['nullable', 'string', 'max:255'],
            'entry_type' => ['nullable', 'string', 'in:NEW,LATERAL'],
            // Academic level field removed
            'intended_degree' => ['nullable', 'string', 'in:PHD in ABE,MS in ABE,MS Agricultural Engineering'],
            'thesis_dissertation_title' => ['nullable', 'string', 'max:1000'],
            'units_required' => ['nullable', 'integer', 'min:0', 'max:200'],
            'units_earned_prior' => ['nullable', 'integer', 'min:0', 'max:200'],
            // Percentage load completed field removed


            // Contact information
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+?\d{1,3}[\s-]?)?(\(?\d{1,4}\)?[\s-]?)?\d{5,11}$/'
            ],

            
            // Detailed Address
            'street' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'zipcode' => ['nullable', 'string', 'max:10'],
            'district' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],

            // Academic dates
            'start_date' => [
                'nullable',
                'date',
                'after:'.Carbon::now()->subYears(10)->format('Y-m-d'),
                'before:'.Carbon::now()->addYears(1)->format('Y-m-d')
            ],
            // Expected completion date field removed

            // Optional academic information


            'gpa' => ['nullable', 'numeric', 'min:1', 'max:5'],

            // Bachelor degree information





            // Profile photo
            'profile_photo' => ['nullable', 'image', 'max:2048'], // 2MB max
            'remove_photo' => ['nullable', 'string', 'in:0,1'],
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
            'first_name' => 'first name',
            'middle_name' => 'middle name',
            'last_name' => 'last name',
            'phone' => 'phone number',
            'gpa' => 'GPA',

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
            'phone.regex' => 'The phone number format is invalid.',
            'start_date.after' => 'The start date cannot be more than 10 years in the past.',
            'start_date.before' => 'The start date cannot be more than 1 year in the future.',
            // Expected completion date validation messages removed
            'profile_photo.max' => 'The profile photo must not be larger than 2MB.',
        ];
    }
}
