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
                    $existingRequest = \App\Models\FundRequest::where([
                        'scholar_profile_id' => Auth::user()->scholarProfile->id,
                        'request_type_id' => $value,
                        'status' => ['Submitted', 'Under Review', 'Approved']
                    ])->exists();

                    if ($existingRequest) {
                        $fail('You already have a pending or approved request of this type.');
                    }
                }
            ],
            'amount' => 'required|numeric|min:0',
            'admin_remarks' => 'nullable|string',
            'status' => 'sometimes|in:Draft,Submitted',
            'document' => 'nullable|file|mimes:pdf|max:10240'
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
