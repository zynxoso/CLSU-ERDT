<?php

namespace App\Services;

use App\Models\FundRequest;
use App\Models\RequestType;
use App\Models\ScholarProfile;
use Illuminate\Support\Facades\Auth;

class FundRequestValidationService
{
    /**
     * Validate fund request creation with comprehensive checks
     *
     * @param array $data
     * @param int $scholarProfileId
     * @return array
     */
    public function validateFundRequestCreation(array $data, int $scholarProfileId): array
    {
        $errors = [];
        $warnings = [];

        // 1. Request Type Limitation - Only one type per submission
        $requestTypeValidation = $this->validateRequestTypeLimitation($data);
        if (!$requestTypeValidation['valid']) {
            $errors = array_merge($errors, $requestTypeValidation['errors']);
        }

        // 2. Approval Restriction Logic - No duplicate approved requests
        $duplicateValidation = $this->validateNoDuplicateApprovedRequests($data['request_type_id'] ?? null, $scholarProfileId);
        if (!$duplicateValidation['valid']) {
            $errors = array_merge($errors, $duplicateValidation['errors']);
        }

        // 3. Amount validation
        $amountValidation = $this->validateAmount($data, $scholarProfileId);
        if (!$amountValidation['valid']) {
            $errors = array_merge($errors, $amountValidation['errors']);
        }
        if (!empty($amountValidation['warnings'])) {
            $warnings = array_merge($warnings, $amountValidation['warnings']);
        }

        // 4. Document validation
        if (isset($data['documents'])) {
            $documentValidation = $this->validateDocuments($data['documents']);
            if (!$documentValidation['valid']) {
                $errors = array_merge($errors, $documentValidation['errors']);
            }
        } elseif (isset($data['document'])) {
            // Backward compatibility for single document
            $documentValidation = $this->validateDocument($data['document']);
            if (!$documentValidation['valid']) {
                $errors = array_merge($errors, $documentValidation['errors']);
            }
        }

        // 5. Role-specific validation
        $roleValidation = $this->validateRoleSpecificRules($data, $scholarProfileId);
        if (!$roleValidation['valid']) {
            $errors = array_merge($errors, $roleValidation['errors']);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }

    /**
     * Validate that only one request type is submitted
     *
     * @param array $data
     * @return array
     */
    private function validateRequestTypeLimitation(array $data): array
    {
        $errors = [];

        // Check if request_type_id is provided and is singular
        if (!isset($data['request_type_id'])) {
            $errors[] = 'Please select a request type. You must choose exactly one type per submission.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Ensure it's not an array (multiple selections)
        if (is_array($data['request_type_id'])) {
            $errors[] = 'Only one request type can be selected per submission.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Validate request type exists and is active
        $requestType = RequestType::find($data['request_type_id']);
        if (!$requestType) {
            $errors[] = 'The selected request type is invalid.';
            return ['valid' => false, 'errors' => $errors];
        }

        if (!$requestType->is_active) {
            $errors[] = 'The selected request type is currently not available.';
            return ['valid' => false, 'errors' => $errors];
        }

        return ['valid' => true, 'errors' => []];
    }

    /**
     * Validate no duplicate approved requests of same type
     *
     * @param int|null $requestTypeId
     * @param int $scholarProfileId
     * @return array
     */
    private function validateNoDuplicateApprovedRequests(?int $requestTypeId, int $scholarProfileId): array
    {
        $errors = [];

        if (!$requestTypeId) {
            return ['valid' => true, 'errors' => []];
        }

        // Check for existing active requests of the same type
        $existingRequest = FundRequest::where('scholar_profile_id', $scholarProfileId)
            ->where('request_type_id', $requestTypeId)
            ->whereIn('status', [
                FundRequest::STATUS_SUBMITTED,
                FundRequest::STATUS_UNDER_REVIEW,
                FundRequest::STATUS_APPROVED
            ])
            ->first();

        if ($existingRequest) {
            $requestType = RequestType::find($requestTypeId);
            $typeName = $requestType ? $requestType->name : 'this type';
            $status = strtolower($existingRequest->status);
            
            $errors[] = "You already have a {$status} request for \"{$typeName}\". Please wait for it to be completed before submitting a new request of the same type.";
            return ['valid' => false, 'errors' => $errors];
        }

        return ['valid' => true, 'errors' => []];
    }

    /**
     * Validate amount against limits and rules
     *
     * @param array $data
     * @param int $scholarProfileId
     * @return array
     */
    private function validateAmount(array $data, int $scholarProfileId): array
    {
        $errors = [];
        $warnings = [];

        if (!isset($data['amount'])) {
            $errors[] = 'Please enter the requested amount.';
            return ['valid' => false, 'errors' => $errors, 'warnings' => []];
        }

        $amount = $data['amount'];

        // Clean amount (remove formatting)
        $cleanAmount = str_replace([',', '₱', ' '], '', $amount);
        
        if (!is_numeric($cleanAmount)) {
            $errors[] = 'The amount must be a valid number.';
            return ['valid' => false, 'errors' => $errors, 'warnings' => []];
        }

        $numericAmount = floatval($cleanAmount);

        // Basic amount validation
        if ($numericAmount < 1) {
            $errors[] = 'The minimum request amount is ₱1.00.';
        }

        if ($numericAmount > 10000000) {
            $errors[] = 'The amount exceeds the maximum allowable limit of ₱10,000,000.00.';
        }

        // Check decimal places
        $decimalPlaces = (explode('.', $cleanAmount)[1] ?? '');
        if (strlen($decimalPlaces) > 2) {
            $errors[] = 'Amount can have at most 2 decimal places.';
        }

        // Validate against request type limits
        if (isset($data['request_type_id']) && !empty($errors) === false) {
            $scholarProfile = ScholarProfile::find($scholarProfileId);
            if ($scholarProfile) {
                $limitValidation = app(FundRequestService::class)->validateFundRequestAmount(
                    $data['request_type_id'],
                    $numericAmount,
                    $scholarProfile->intended_degree
                );

                if ($limitValidation) {
                    if (isset($limitValidation['amount'])) {
                        $errors[] = $limitValidation['amount'];
                    }
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }

    /**
     * Validate multiple document uploads
     *
     * @param array $documents
     * @return array
     */
    private function validateDocuments(array $documents): array
    {
        $errors = [];

        if (empty($documents)) {
            return ['valid' => true, 'errors' => []]; // Documents are optional
        }

        // Limit to maximum 5 documents
        if (count($documents) > 5) {
            $errors[] = 'You can upload a maximum of 5 documents.';
            return ['valid' => false, 'errors' => $errors];
        }

        foreach ($documents as $index => $document) {
            if (!$document) {
                continue;
            }

            $documentValidation = $this->validateSingleDocument($document, $index + 1);
            if (!$documentValidation['valid']) {
                $errors = array_merge($errors, $documentValidation['errors']);
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate single document upload
     *
     * @param mixed $document
     * @param int $documentNumber
     * @return array
     */
    private function validateSingleDocument($document, int $documentNumber = 1): array
    {
        $errors = [];
        $prefix = $documentNumber > 1 ? "Document {$documentNumber}: " : '';

        if (!$document) {
            return ['valid' => true, 'errors' => []]; // Document is optional
        }

        // Check if it's a valid uploaded file
        if (!$document->isValid()) {
            $errors[] = $prefix . 'The uploaded file is corrupted or invalid. Please try uploading again.';
            return ['valid' => false, 'errors' => $errors];
        }

        // File type validation
        $extension = strtolower($document->getClientOriginalExtension());
        $mimeType = $document->getMimeType();

        if ($extension !== 'pdf' || $mimeType !== 'application/pdf') {
            $errors[] = $prefix . 'Only PDF files are allowed for document uploads.';
        }

        // File size validation (5MB)
        if ($document->getSize() > 5242880) {
            $errors[] = $prefix . 'The document file size must not exceed 5MB.';
        }

        // Minimum file size (1KB)
        if ($document->getSize() < 1024) {
            $errors[] = $prefix . 'The document file appears to be too small or corrupted.';
        }

        // File name validation
        $fileName = $document->getClientOriginalName();
        if (strlen($fileName) > 255) {
            $errors[] = $prefix . 'The file name is too long. Please rename the file and try again.';
        }

        // Check for suspicious characters
        if (preg_match('/[<>:"|?*]/', $fileName)) {
            $errors[] = $prefix . 'The file name contains invalid characters. Please rename the file.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate document upload (backward compatibility)
     *
     * @param mixed $document
     * @return array
     */
    private function validateDocument($document): array
    {
        return $this->validateSingleDocument($document, 1);
    }



    /**
     * Validate role-specific rules
     *
     * @param array $data
     * @param int $scholarProfileId
     * @return array
     */
    private function validateRoleSpecificRules(array $data, int $scholarProfileId): array
    {
        $errors = [];
        $user = Auth::user();

        if (!$user) {
            $errors[] = 'Authentication required.';
            return ['valid' => false, 'errors' => $errors];
        }

        if ($user->role === 'scholar') {
            // Scholar-specific validations
            
            // Ensure scholar owns the profile
            if ($user->scholarProfile->id !== $scholarProfileId) {
                $errors[] = 'You can only create requests for your own profile.';
            }

            // Check if request type is requestable by scholars
            if (isset($data['request_type_id'])) {
                $requestType = RequestType::find($data['request_type_id']);
                if ($requestType && !$requestType->is_requestable) {
                    $errors[] = 'This request type is not available for scholar requests. Please contact an administrator if you need assistance.';
                }
            }

            // Scholars cannot set certain fields
            $restrictedFields = ['reviewed_by', 'reviewed_at', 'rejection_reason'];
            foreach ($restrictedFields as $field) {
                if (isset($data[$field])) {
                    $errors[] = 'Invalid request data detected.';
                    break;
                }
            }

        } elseif ($user->role === 'admin') {
            // Admin-specific validations
            
            // Admins can create for any scholar, but must specify valid scholar_profile_id
            if (isset($data['scholar_profile_id']) && $data['scholar_profile_id'] !== $scholarProfileId) {
                $errors[] = 'Scholar profile ID mismatch.';
            }

        } else {
            $errors[] = 'Insufficient permissions to create fund requests.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get validation error messages formatted for display
     *
     * @param array $errors
     * @return string
     */
    public function formatErrorMessages(array $errors): string
    {
        if (empty($errors)) {
            return '';
        }

        $formatted = "Please correct the following errors:\n\n";
        foreach ($errors as $index => $error) {
            $formatted .= ($index + 1) . ". " . $error . "\n";
        }

        return $formatted;
    }

    /**
     * Check if user can create fund request
     *
     * @param int $scholarProfileId
     * @return array
     */
    public function canCreateFundRequest(int $scholarProfileId): array
    {
        $user = Auth::user();
        $errors = [];

        if (!$user) {
            $errors[] = 'Authentication required.';
            return ['can_create' => false, 'errors' => $errors];
        }

        // Check user role
        if (!in_array($user->role, ['scholar', 'admin'])) {
            $errors[] = 'Insufficient permissions to create fund requests.';
            return ['can_create' => false, 'errors' => $errors];
        }

        // Check scholar profile exists
        $scholarProfile = ScholarProfile::find($scholarProfileId);
        if (!$scholarProfile) {
            $errors[] = 'Scholar profile not found.';
            return ['can_create' => false, 'errors' => $errors];
        }

        // For scholars, ensure they own the profile
        if ($user->role === 'scholar' && $user->scholarProfile->id !== $scholarProfileId) {
            $errors[] = 'You can only create requests for your own profile.';
            return ['can_create' => false, 'errors' => $errors];
        }

        return ['can_create' => true, 'errors' => []];
    }
}