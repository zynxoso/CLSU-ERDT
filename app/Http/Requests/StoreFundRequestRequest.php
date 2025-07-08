<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFundRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->scholarProfile !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'request_type_id' => [
                'required',
                'exists:request_types,id',
                function ($attribute, $value, $fail) {
                    // Check if request type is active
                    $requestType = \App\Models\RequestType::find($value);
                    if ($requestType && !$requestType->is_active) {
                        $fail('The selected request type is currently not available.');
                    }

                    // Check for existing pending requests of the same type
                    $existingRequest = \App\Models\FundRequest::where([
                        'scholar_profile_id' => Auth::user()->scholarProfile->id,
                        'request_type_id' => $value,
                    ])->whereIn('status', ['Submitted', 'Under Review', 'Approved'])->exists();

                    if ($existingRequest) {
                        $fail('You already have a pending or approved request of this type.');
                    }
                }
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999',
                'regex:/^\d+(\.\d{1,2})?$/', // Allow up to 2 decimal places
                function ($attribute, $value, $fail) {
                    // Additional amount validation will be handled by the service layer
                    if ($value <= 0) {
                        $fail('The amount must be greater than zero.');
                    }

                    // Check for reasonable amount (not too small)
                    if ($value < 1) {
                        $fail('The minimum amount is ₱1.00.');
                    }
                }
            ],
            'admin_remarks' => 'nullable|string|max:1000',
            'status' => 'sometimes|in:Draft,Submitted',
            'document' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240', // 10MB
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Additional file validation
                        $extension = strtolower($value->getClientOriginalExtension());
                        $mimeType = $value->getMimeType();

                        // Strict PDF validation
                        if ($extension !== 'pdf' || $mimeType !== 'application/pdf') {
                            $fail('Only PDF files are allowed.');
                        }

                        // Check file size more strictly
                        if ($value->getSize() > 10485760) { // 10MB in bytes
                            $fail('The document size must not exceed 10MB.');
                        }

                        // Check for minimum file size (avoid empty files)
                        if ($value->getSize() < 1024) { // 1KB minimum
                            $fail('The document file appears to be too small or corrupted.');
                        }
                    }
                }
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'request_type_id.required' => 'Please select a request type.',
            'request_type_id.exists' => 'The selected request type is invalid.',
            'amount.required' => 'Please enter the requested amount.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be greater than zero.',
            'document.file' => 'The uploaded document must be a file.',
            'document.mimes' => 'The document must be a PDF file.',
            'document.max' => 'The document may not be greater than 10MB.'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if scholar is eligible (PhD or Master's level only)
            $scholarProfile = Auth::user()->scholarProfile;
            if ($scholarProfile && !in_array($scholarProfile->degree_level, ['PhD', 'Master\'s', 'Masters', 'Doctoral'])) {
                $validator->errors()->add('request_type_id', 'Fund requests are only available for PhD and Master\'s level scholars.');
            }

            // Additional security validation - CyberSweep enhancement
            if ($this->hasFile('document')) {
                $file = $this->file('document');

                // Verify file integrity
                if (!$file->isValid()) {
                    $validator->errors()->add('document', 'The uploaded file is corrupted or invalid.');
                    return;
                }

                // Double-check file extension matches actual content type for PDF
                $extension = strtolower($file->getClientOriginalExtension());
                $mimeType = $file->getMimeType();

                if ($extension === 'pdf' && $mimeType !== 'application/pdf') {
                    $validator->errors()->add('document', 'The file extension does not match its content. Only PDF files are allowed.');
                }
            }
        });
    }
}
