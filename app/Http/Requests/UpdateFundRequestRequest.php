<?php

namespace App\Http\Requests;

use App\Models\FundRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFundRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $fundRequest = $this->route('fundRequest');
        $user = Auth::user();

        // Check if user is authorized to update this request
        if ($user->role === 'scholar' && $user->scholarProfile->id !== $fundRequest->scholar_profile_id) {
            return false;
        }

        // Only draft requests can be updated
        if ($fundRequest->status !== 'Draft') {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'request_type_id' => 'required|exists:request_types,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'sometimes|in:Draft,Submitted'
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
            'amount.min' => 'The amount must be greater than zero.'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function failedAuthorization()
    {
        $fundRequest = $this->route('fundRequest');

        if ($fundRequest->status !== 'Draft') {
            abort(403, 'Only draft requests can be updated.');
        }

        abort(403, 'You are not authorized to update this request.');
    }
}
