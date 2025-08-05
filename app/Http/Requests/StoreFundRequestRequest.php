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
                    $user = Auth::user();
                    
                    // Check if request type is active
                    $requestType = \App\Models\RequestType::find($value);
                    if (!$requestType || !$requestType->is_active) {
                        $fail('The selected request type is currently not available.');
                        return;
                    }

                    // Role-specific validation
                    if ($user->role === 'scholar') {
                        // Check if scholars are trying to request non-requestable types (like stipends)
                        if (!$requestType->is_requestable) {
                            $fail('This request type is not available for scholar requests. Please contact an administrator if you need assistance.');
                            return;
                        }

                        // Scholar-specific duplicate check: No active requests of same type
                        $existingActiveRequest = \App\Models\FundRequest::where([
                            'scholar_profile_id' => $user->scholarProfile->id,
                            'request_type_id' => $value,
                        ])->whereIn('status', [
                            \App\Models\FundRequest::STATUS_SUBMITTED, 
                            \App\Models\FundRequest::STATUS_UNDER_REVIEW, 
                            \App\Models\FundRequest::STATUS_APPROVED
                        ])->first();

                        if ($existingActiveRequest) {
                            $statusText = strtolower($existingActiveRequest->status);
                            $fail("You already have a {$statusText} request of this type. Please wait for it to be completed before submitting a new request of the same type.");
                            return;
                        }
                    } elseif ($user->role === 'admin') {
                        // Admin can create any active request type, but still check for duplicates if creating for a scholar
                        if ($this->has('scholar_profile_id')) {
                            $scholarProfileId = $this->input('scholar_profile_id');
                            $existingActiveRequest = \App\Models\FundRequest::where([
                                'scholar_profile_id' => $scholarProfileId,
                                'request_type_id' => $value,
                            ])->whereIn('status', [
                                \App\Models\FundRequest::STATUS_SUBMITTED, 
                                \App\Models\FundRequest::STATUS_UNDER_REVIEW, 
                                \App\Models\FundRequest::STATUS_APPROVED
                            ])->first();

                            if ($existingActiveRequest) {
                                $statusText = strtolower($existingActiveRequest->status);
                                $fail("This scholar already has a {$statusText} request of this type. Please wait for it to be completed before creating a new request of the same type.");
                                return;
                            }
                        }
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
            'status' => [
                'sometimes',
                'in:' . \App\Models\FundRequest::STATUS_DRAFT . ',' . \App\Models\FundRequest::STATUS_SUBMITTED,
                function ($attribute, $value, $fail) {
                    // Ensure only one status is submitted at a time
                    if ($value && !in_array($value, [\App\Models\FundRequest::STATUS_DRAFT, \App\Models\FundRequest::STATUS_SUBMITTED])) {
                        $fail('Invalid status provided. Only draft or submitted status is allowed.');
                    }
                }
            ],
            'scholar_profile_id' => [
                'sometimes',
                'exists:scholar_profiles,id',
                function ($attribute, $value, $fail) {
                    // Only admins can specify scholar_profile_id
                    if ($value && Auth::user()->role !== 'admin') {
                        $fail('You are not authorized to create requests for other scholars.');
                    }
                }
            ],
            'document' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120', // 5MB
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
                        if ($value->getSize() > 5242880) { // 5MB in bytes
                            $fail('The document size must not exceed 5MB.');
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
            'request_type_id.required' => 'Please select a request type. You must choose exactly one type per submission.',
            'request_type_id.exists' => 'The selected request type is invalid or no longer available.',
            'amount.required' => 'Please enter the requested amount.',
            'amount.numeric' => 'The amount must be a valid number.',
            'amount.min' => 'The amount must be greater than zero.',
            'amount.regex' => 'The amount format is invalid. Please use up to 2 decimal places.',
            'document.file' => 'The uploaded document must be a valid file.',
            'document.mimes' => 'Only PDF files are allowed for document uploads.',
            'document.max' => 'The document file size must not exceed 5MB.',
            'status.in' => 'Invalid submission status. Please try again.',
            'scholar_profile_id.exists' => 'The specified scholar profile does not exist.',
            'admin_remarks.max' => 'Admin remarks must not exceed 1000 characters.'
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
            $user = Auth::user();
            
            // Ensure user has scholar profile if they're a scholar
            if ($user->role === 'scholar' && !$user->scholarProfile) {
                $validator->errors()->add('scholar_profile', 'Scholar profile not found. Please complete your profile setup first.');
                return;
            }

            // Validate that only one request type is being submitted
            $requestTypeId = $this->input('request_type_id');
            if ($requestTypeId) {
                // Additional check to ensure no manipulation of multiple types
                $allRequestTypes = $this->input();
                $typeFields = array_filter(array_keys($allRequestTypes), function($key) {
                    return strpos($key, 'request_type') !== false;
                });
                
                if (count($typeFields) > 1) {
                    $validator->errors()->add('request_type_id', 'Only one request type can be selected per submission.');
                }
            }

            // Enhanced amount validation
            $amount = $this->input('amount');
            if ($amount && $requestTypeId) {
                // Remove formatting and validate numeric value
                $cleanAmount = str_replace([',', '₱', ' '], '', $amount);
                if (!is_numeric($cleanAmount)) {
                    $validator->errors()->add('amount', 'The amount must be a valid number.');
                } else {
                    $numericAmount = floatval($cleanAmount);
                    
                    // Check minimum amount
                    if ($numericAmount < 1) {
                        $validator->errors()->add('amount', 'The minimum request amount is ₱1.00.');
                    }
                    
                    // Check maximum reasonable amount
                    if ($numericAmount > 10000000) { // 10 million
                        $validator->errors()->add('amount', 'The amount exceeds the maximum allowable limit.');
                    }
                }
            }

            // Enhanced document validation - CyberSweep enhancement
            $document = request()->file('document');
            if ($document !== null) {
                // Verify file integrity
                if (!$document->isValid()) {
                    $validator->errors()->add('document', 'The uploaded file is corrupted or invalid. Please try uploading again.');
                    return;
                }

                // Double-check file extension matches actual content type for PDF
                $extension = strtolower($document->getClientOriginalExtension());
                $mimeType = $document->getMimeType();

                if ($extension === 'pdf' && $mimeType !== 'application/pdf') {
                    $validator->errors()->add('document', 'The file extension does not match its content. Only genuine PDF files are allowed.');
                }

                // Additional security checks
                $fileName = $document->getClientOriginalName();
                if (strlen($fileName) > 255) {
                    $validator->errors()->add('document', 'The file name is too long. Please rename the file and try again.');
                }

                // Check for suspicious file patterns
                if (preg_match('/[<>:"|?*]/', $fileName)) {
                    $validator->errors()->add('document', 'The file name contains invalid characters. Please rename the file.');
                }
            }

            // Role-specific additional validations
            if ($user->role === 'scholar') {
                // Ensure scholar is not trying to bypass restrictions
                if ($this->has('reviewed_by') || $this->has('reviewed_at')) {
                    $validator->errors()->add('general', 'Invalid request data detected.');
                }
            }
        });
    }
}
