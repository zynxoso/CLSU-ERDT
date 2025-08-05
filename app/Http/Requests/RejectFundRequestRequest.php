<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

        $fundRequestId = Route::current()->parameter('id');
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
            'rejection_reason' => 'required|string|max:1000',
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
            'rejection_reason.required' => 'Please provide a reason for rejecting this request.',
            'rejection_reason.max' => 'The rejection reason may not be greater than 1000 characters.'
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

        $fundRequestId = Route::current()->parameter('id');
        $fundRequest = \App\Models\FundRequest::findOrFail($fundRequestId);

        if (!in_array($fundRequest->status, ['Submitted', 'Under Review'])) {
            abort(403, 'Only submitted or under review requests can be rejected.');
        }
    }
}
