<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * @method mixed all(string|null $keys = null)
 * @method array validated($key = null, $default = null)
 */
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

            // Contact information
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+?\d{1,3}[\s-]?)?(\(?\d{1,4}\)?[\s-]?)?\d{5,11}$/'
            ],

            // Address information
            'street' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'town' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'zipcode' => ['nullable', 'string', 'max:10'],
            'district' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
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
            'first_name' => 'first name',
            'middle_name' => 'middle name',
            'last_name' => 'last name',
            'phone' => 'phone number',
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
        ];
    }

    /**
     * Get the validated data from the request with debugging.
     *
     * @param  array|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        Log::info('ScholarProfileUpdateRequest validation started', [
            'user_id' => Auth::id(),
            'request_data' => $this->all()
        ]);

        try {
            $validated = parent::validated($key, $default);
            Log::info('ScholarProfileUpdateRequest validation passed', [
                'user_id' => Auth::id(),
                'validated_data' => $validated
            ]);
            return $validated;
        } catch (\Exception $e) {
            Log::error('ScholarProfileUpdateRequest validation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'request_data' => $this->all()
            ]);
            throw $e;
        }
    }
}
