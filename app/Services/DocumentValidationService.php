<?php

namespace App\Services;

use App\Models\RequestType;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentValidationService
{

    /**
     * Validate uploaded document against request type requirements.
     */
    public function validateDocument(UploadedFile $file, RequestType $requestType, ?string $documentCategory = null): ValidationResult
    {
        $result = new ValidationResult();
        
        try {
            // Basic file validation
            $this->validateBasicFile($file, $result);
            
            // Request type specific validation
            $this->validateAgainstRequestType($file, $requestType, $result);
            
            // Document category validation
            if ($documentCategory) {
                $this->validateDocumentCategory($file, $requestType, $documentCategory, $result);
            }
            
            // Security validation
            $this->validateFileSecurity($file, $result);
            
            // Set metadata
            $result->metadata = [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Document validation error: ' . $e->getMessage());
            $result->addError('An error occurred during document validation.');
        }
        
        return $result;
    }

    /**
     * Validate basic file properties.
     */
    private function validateBasicFile(UploadedFile $file, ValidationResult $result): void
    {
        // Check if file is valid
        if (!$file->isValid()) {
            $result->addError('The uploaded file is corrupted or invalid.');
            return;
        }
        
        // Check file size (max 50MB)
        $maxSize = 50 * 1024 * 1024; // 50MB in bytes
        if ($file->getSize() > $maxSize) {
            $result->addError('File size exceeds the maximum allowed size of 50MB.');
        }
        
        // Check if file has content
        if ($file->getSize() === 0) {
            $result->addError('The uploaded file is empty.');
        }
    }

    /**
     * Validate file against request type requirements.
     */
    private function validateAgainstRequestType(UploadedFile $file, RequestType $requestType, ValidationResult $result): void
    {
        $requirements = $requestType->getDocumentRequirements();
        
        if (empty($requirements)) {
            $result->addWarning('No specific document requirements defined for this request type.');
            return;
        }
        
        // Validate file type
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedTypes = $requirements['file_types'] ?? [];
        
        if (!empty($allowedTypes) && !in_array($extension, $allowedTypes)) {
            $result->addError('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes));
        }
        
        // Validate file size
        $maxSizeKB = $requirements['max_size'] ?? 5120; // Default 5MB
        $fileSizeKB = $file->getSize() / 1024;
        
        if ($fileSizeKB > $maxSizeKB) {
            $maxSizeMB = $maxSizeKB / 1024;
            $result->addError("File size exceeds the maximum allowed size of {$maxSizeMB}MB for this request type.");
        }
    }

    /**
     * Validate document category against required documents.
     */
    private function validateDocumentCategory(UploadedFile $file, RequestType $requestType, string $category, ValidationResult $result): void
    {
        $requirements = $requestType->getDocumentRequirements();
        $requiredDocs = $requirements['documents'] ?? [];
        
        if (!empty($requiredDocs) && !in_array($category, $requiredDocs)) {
            $result->addError('Invalid document category. Required documents: ' . implode(', ', $requiredDocs));
        }
    }

    /**
     * Validate file security (check for malicious content).
     */
    private function validateFileSecurity(UploadedFile $file, ValidationResult $result): void
    {
        // Check MIME type against extension
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        
        $allowedMimeTypes = [
            'pdf' => ['application/pdf'],
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        ];
        
        if (isset($allowedMimeTypes[$extension])) {
            if (!in_array($mimeType, $allowedMimeTypes[$extension])) {
                $result->addError('File type mismatch. The file extension does not match the file content.');
            }
        }
        
        // Check for executable files
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'];
        if (in_array($extension, $dangerousExtensions)) {
            $result->addError('Executable files are not allowed.');
        }
    }

    /**
     * Get required documents for a request type.
     */
    public function getRequiredDocuments(RequestType $requestType): array
    {
        $requirements = $requestType->getDocumentRequirements();
        return $requirements['documents'] ?? [];
    }

    /**
     * Get document guidance for a request type.
     */
    public function getDocumentGuidance(RequestType $requestType): string
    {
        $requirements = $requestType->getDocumentRequirements();
        return $requirements['guidance'] ?? 'Please upload the required documents for this request type.';
    }

    /**
     * Get allowed file types for a request type.
     */
    public function getAllowedFileTypes(RequestType $requestType): array
    {
        $requirements = $requestType->getDocumentRequirements();
        return $requirements['file_types'] ?? ['pdf', 'jpg', 'jpeg', 'png'];
    }

    /**
     * Get maximum file size for a request type (in KB).
     */
    public function getMaxFileSize(RequestType $requestType): int
    {
        $requirements = $requestType->getDocumentRequirements();
        return $requirements['max_size'] ?? 5120; // Default 5MB
    }

    /**
     * Validate multiple documents for a fund request.
     */
    public function validateMultipleDocuments(array $files, RequestType $requestType): array
    {
        $results = [];
        $requiredDocs = $this->getRequiredDocuments($requestType);
        $uploadedCategories = [];
        
        foreach ($files as $category => $file) {
            if ($file instanceof UploadedFile) {
                $results[$category] = $this->validateDocument($file, $requestType, $category);
                $uploadedCategories[] = $category;
            }
        }
        
        // Check if all required documents are uploaded
        $missingDocs = array_diff($requiredDocs, $uploadedCategories);
        if (!empty($missingDocs)) {
            $results['_missing'] = new ValidationResult(false, [
                'Missing required documents: ' . implode(', ', $missingDocs)
            ]);
        }
        
        return $results;
    }

    /**
     * Store validated document.
     */
    public function storeDocument(UploadedFile $file, ValidationResult $validation, array $documentData): ?Document
    {
        if (!$validation->isValid) {
            return null;
        }
        
        try {
            // Generate unique filename
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $filename, 'private');
            
            // Create document record
            $document = Document::create(array_merge($documentData, [
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'validation_status' => $validation->hasWarnings() ? 'Requires Review' : 'Valid',
                'validation_notes' => implode('; ', array_merge($validation->errors, $validation->warnings)),
            ]));
            
            return $document;
            
        } catch (\Exception $e) {
            Log::error('Document storage error: ' . $e->getMessage());
            return null;
        }
    }
}