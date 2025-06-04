<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RejectFundRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admins can reject fund requests
        if (Auth::user()->role !== 'admin') {
            return false;
        }

        $fundRequestId = $this->route('id');
        $fundRequest = \App\Models\FundRequest::findOrFail($fundRequestId);

        // Only submitted or under review requests can be rejected
        return in_array($fundRequest->status, ['Submitted', 'Under Review']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin_notes' => 'required|string|max:1000',
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
            'admin_notes.required' => 'Please provide a reason for rejecting this request.',
            'admin_notes.max' => 'The rejection reason may not be greater than 1000 characters.'
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     */
    public function failedAuthorization()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Only administrators can reject fund requests.');
        }

        $fundRequestId = $this->route('id');
        $fundRequest = \App\Models\FundRequest::findOrFail($fundRequestId);

        if (!in_array($fundRequest->status, ['Submitted', 'Under Review'])) {
            abort(403, 'Only submitted or under review requests can be rejected.');
        }
    }
}
