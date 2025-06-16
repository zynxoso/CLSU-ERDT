<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileSecurityService
{
    /**
     * Maximum file size in bytes (20MB)
     */
    private const MAX_FILE_SIZE = 20 * 1024 * 1024;

    /**
     * Allowed file extensions with their corresponding MIME types
     */
    private const ALLOWED_FILE_TYPES = [
        'pdf' => [
            'application/pdf',
            'application/x-pdf'
        ],
        'jpg' => [
            'image/jpeg',
            'image/pjpeg'
        ],
        'jpeg' => [
            'image/jpeg',
            'image/pjpeg'
        ],
        'png' => [
            'image/png'
        ],
        'doc' => [
            'application/msword'
        ],
        'docx' => [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]
    ];

    /**
     * File signatures (magic numbers) for validation
     */
    private const FILE_SIGNATURES = [
        'pdf' => ['%PDF'],
        'jpg' => ['\xFF\xD8\xFF'],
        'jpeg' => ['\xFF\xD8\xFF'],
        'png' => ['\x89PNG\r\n\x1a\n'],
        'doc' => ['\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1'],
        'docx' => ['PK\x03\x04']
    ];

    /**
     * Suspicious content patterns to detect
     */
    private const SUSPICIOUS_PATTERNS = [
        // PHP code
        '/<\?php/i',
        '/<\?=/i',
        '/<\?/i',

        // JavaScript
        '/<script[^>]*>/i',
        '/javascript:/i',
        '/on\w+\s*=/i',

        // Executable code
        '/eval\s*\(/i',
        '/exec\s*\(/i',
        '/system\s*\(/i',
        '/passthru\s*\(/i',
        '/shell_exec\s*\(/i',
        '/`[^`]*`/i',

        // SQL injection
        '/union\s+select/i',
        '/drop\s+table/i',
        '/delete\s+from/i',

        // Command injection
        '/;\s*rm\s+/i',
        '/;\s*cat\s+/i',
        '/;\s*ls\s+/i',
        '/\|\s*nc\s+/i',

        // Malicious URLs
        '/http:\/\/[^\s]*\.exe/i',
        '/https:\/\/[^\s]*\.exe/i',
        '/ftp:\/\/[^\s]*\.exe/i'
    ];

    /**
     * Validate uploaded file with comprehensive security checks
     */
    public function validateFile(UploadedFile $file, ?string $userId = null): array
    {
        $result = [
            'valid' => true,
            'errors' => [],
            'warnings' => [],
            'secure_filename' => null,
            'file_info' => []
        ];

        try {
            // Basic file validation
            $this->validateBasicFileProperties($file, $result);

            // File type validation
            $this->validateFileType($file, $result);

            // File signature validation
            $this->validateFileSignature($file, $result);

            // Content security scanning
            $this->scanFileContent($file, $result);

            // Virus scanning
            $this->scanFileForViruses($file, $result);

            // Generate secure filename
            $result['secure_filename'] = $this->generateSecureFilename($file);

            // Collect file information
            $result['file_info'] = $this->collectFileInfo($file);

            // Log security scan
            $this->logSecurityScan($file, $result, $userId);

        } catch (\Exception $e) {
            $result['valid'] = false;
            $result['errors'][] = 'File validation failed: ' . $e->getMessage();

            Log::error('File security validation error', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
        }

        return $result;
    }

    /**
     * Validate basic file properties
     */
    private function validateBasicFileProperties(UploadedFile $file, array &$result): void
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            $result['valid'] = false;
            $result['errors'][] = 'File size exceeds maximum allowed size of ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB';
        }

        // Check if file is actually uploaded
        if (!$file->isValid()) {
            $result['valid'] = false;
            $result['errors'][] = 'File upload failed or file is corrupted';
        }

        // Check for suspicious filename patterns
        $filename = $file->getClientOriginalName();
        if (preg_match('/[\x00-\x1f\x7f-\x9f]/', $filename)) {
            $result['valid'] = false;
            $result['errors'][] = 'Filename contains invalid characters';
        }

        // Check for path traversal attempts
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            $result['valid'] = false;
            $result['errors'][] = 'Filename contains path traversal patterns';
        }
    }

    /**
     * Validate file type against allowed types
     */
    private function validateFileType(UploadedFile $file, array &$result): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        // Check if extension is allowed
        if (!array_key_exists($extension, self::ALLOWED_FILE_TYPES)) {
            $result['valid'] = false;
            $result['errors'][] = "File extension '{$extension}' is not allowed";
            return;
        }

        // Check if MIME type matches extension
        $allowedMimeTypes = self::ALLOWED_FILE_TYPES[$extension];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            $result['valid'] = false;
            $result['errors'][] = "MIME type '{$mimeType}' does not match file extension '{$extension}'";
        }
    }

    /**
     * Validate file signature (magic numbers) with improved checks
     */
    private function validateFileSignature(UploadedFile $file, array &$result): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!array_key_exists($extension, self::FILE_SIGNATURES)) {
            $result['warnings'][] = "No signature validation available for '{$extension}' files";
            return;
        }

        $fileContent = file_get_contents($file->getRealPath(), false, null, 0, 4096); // Read more bytes for better validation
        $signatures = self::FILE_SIGNATURES[$extension];
        $signatureFound = false;

        foreach ($signatures as $signature) {
            if (strpos($fileContent, $signature) !== false) { // Check if signature exists anywhere in the first 4KB
                $signatureFound = true;
                break;
            }
        }

        if (!$signatureFound) {
            $result['valid'] = false;
            $result['errors'][] = "File signature does not match expected format for '{$extension}' files";
        }
    }

    /**
     * Scan file content for viruses using an external API
     */
    private function scanFileForViruses(UploadedFile $file, array &$result): void
    {
        try {
            // Placeholder for virus scanning - can be integrated with actual virus scanner later
            // For now, we'll perform basic checks for known malicious patterns
            $content = file_get_contents($file->getRealPath());

            // Check for executable signatures that shouldn't be in documents
            $maliciousSignatures = [
                'MZ', // Windows executable
                '\x7fELF', // Linux executable
                '#!/bin/', // Shell script
                '<?php', // PHP script in non-PHP files
            ];

            foreach ($maliciousSignatures as $signature) {
                if (strpos($content, $signature) === 0) {
                    $result['valid'] = false;
                    $result['errors'][] = 'File contains potentially malicious executable content';
                    Log::warning('Potentially malicious file detected', [
                        'file' => $file->getClientOriginalName(),
                        'signature' => $signature
                    ]);
                    return;
                }
            }

            // TODO: Integrate with actual virus scanner API (ClamAV, VirusTotal, etc.)
            Log::info('Virus scan completed (placeholder implementation)', [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize()
            ]);

        } catch (\Exception $e) {
            $result['warnings'][] = 'Virus scanning failed: ' . $e->getMessage();
            Log::error('Virus scanning error', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Validate uploaded file with comprehensive security checks
     */
    public function validateUploadedFile(UploadedFile $file, ?string $userId = null): array
    {
        $result = [
            'valid' => true,
            'errors' => [],
            'warnings' => [],
            'secure_filename' => null,
            'file_info' => []
        ];

        try {
            // Basic file validation
            $this->validateBasicFileProperties($file, $result);

            // File type validation
            $this->validateFileType($file, $result);

            // File signature validation
            $this->validateFileSignature($file, $result);

            // Content security scanning
            $this->scanFileContent($file, $result);

            // Virus scanning
            $this->scanFileForViruses($file, $result);

            // Generate secure filename
            $result['secure_filename'] = $this->generateSecureFilename($file);

            // Collect file information
            $result['file_info'] = $this->collectFileInfo($file);

            // Log security scan
            $this->logSecurityScan($file, $result, $userId);

        } catch (\Exception $e) {
            $result['valid'] = false;
            $result['errors'][] = 'File validation failed: ' . $e->getMessage();

            Log::error('File security validation error', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
        }

        return $result;
    }

    /**
     * Scan file content for suspicious patterns
     */
    private function scanFileContent(UploadedFile $file, array &$result): void
    {
        $content = file_get_contents($file->getRealPath());
        $suspiciousPatterns = [];

        foreach (self::SUSPICIOUS_PATTERNS as $pattern) {
            if (preg_match($pattern, $content)) {
                $suspiciousPatterns[] = $pattern;
            }
        }

        if (!empty($suspiciousPatterns)) {
            $result['valid'] = false;
            $result['errors'][] = 'File contains suspicious content patterns';

            Log::warning('Suspicious content detected in uploaded file', [
                'file' => $file->getClientOriginalName(),
                'patterns' => $suspiciousPatterns,
                'size' => $file->getSize()
            ]);
        }
    }

    /**
     * Generate secure filename
     */
    private function generateSecureFilename(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());

        // Sanitize filename
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName);
        $safeName = preg_replace('/_{2,}/', '_', $safeName);
        $safeName = trim($safeName, '_');

        // Ensure filename is not empty
        if (empty($safeName)) {
            $safeName = 'document';
        }

        // Limit filename length
        $safeName = Str::limit($safeName, 50, '');

        // Add timestamp and random string for uniqueness
        $timestamp = time();
        $random = Str::random(8);

        return "{$safeName}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Collect file information
     */
    private function collectFileInfo(UploadedFile $file): array
    {
        return [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => strtolower($file->getClientOriginalExtension()),
            'hash_md5' => md5_file($file->getRealPath()),
            'hash_sha256' => hash_file('sha256', $file->getRealPath())
        ];
    }

    /**
     * Log security scan results
     */
    private function logSecurityScan(UploadedFile $file, array $result, ?string $userId): void
    {
        Log::info('File security scan completed', [
            'file' => $file->getClientOriginalName(),
            'valid' => $result['valid'],
            'errors_count' => count($result['errors']),
            'warnings_count' => count($result['warnings']),
            'user_id' => $userId,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ]);
    }

    /**
     * Set secure file permissions
     */
    public function setSecureFilePermissions(string $filePath): bool
    {
        try {
            $fullPath = storage_path('app/public/' . $filePath);

            // Set file permissions to read-only for owner and group, no permissions for others
            chmod($fullPath, 0640);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to set secure file permissions', [
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Create secure storage directory with proper permissions
     */
    public function createSecureDirectory(string $path): bool
    {
        try {
            $fullPath = storage_path('app/public/' . $path);

            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0750, true);
            }

            // Create .htaccess file to prevent direct access
            $htaccessPath = $fullPath . '/.htaccess';
            if (!file_exists($htaccessPath)) {
                file_put_contents($htaccessPath, "Options -Indexes\nOptions -ExecCGI\nAddHandler cgi-script .php .pl .py .jsp .asp .sh .cgi\n");
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create secure directory', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Quarantine suspicious file
     */
    public function quarantineFile(UploadedFile $file, string $reason): string
    {
        $quarantinePath = 'quarantine/' . date('Y/m/d');
        $this->createSecureDirectory($quarantinePath);

        $filename = $this->generateSecureFilename($file);
        $fullPath = $quarantinePath . '/' . $filename;

        // Move file to quarantine
        $file->storeAs($quarantinePath, $filename, 'public');

        // Log quarantine action
        Log::warning('File quarantined', [
            'original_name' => $file->getClientOriginalName(),
            'quarantine_path' => $fullPath,
            'reason' => $reason,
            'size' => $file->getSize()
        ]);

        return $fullPath;
    }

    /**
     * Store encrypted file with security validation
     */
    public function storeEncryptedFile(UploadedFile $file, string $path, ?string $userId = null): array
    {
        // First validate the file
        $validation = $this->validateFile($file, $userId);

        if (!$validation['valid']) {
            throw new \Exception('File validation failed: ' . implode(', ', $validation['errors']));
        }

        try {
            // Generate a unique filename
            $filename = $validation['secure_filename'];
            $fullPath = $path . '/' . $filename;

            // Read file content
            $content = file_get_contents($file->getRealPath());

            // Encrypt the content
            $encryptedContent = \Illuminate\Support\Facades\Crypt::encrypt($content);

            // Store the encrypted content
            \Illuminate\Support\Facades\Storage::put($fullPath, $encryptedContent);

            // Set secure permissions
            $this->setSecureFilePermissions($fullPath);

            // Log the secure storage
            Log::info('File stored with encryption', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $fullPath,
                'user_id' => $userId,
                'file_size' => $file->getSize(),
                'timestamp' => now()
            ]);

            return [
                'success' => true,
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $fullPath,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'file_info' => $validation['file_info']
            ];

        } catch (\Exception $e) {
            Log::error('Failed to store encrypted file', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);

            throw new \Exception('Failed to store encrypted file: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve and decrypt file content
     */
    public function retrieveEncryptedFile(string $path, ?string $userId = null): string
    {
        try {
            // Check if file exists
            if (!\Illuminate\Support\Facades\Storage::exists($path)) {
                throw new \Exception('File not found: ' . $path);
            }

            // Get encrypted content
            $encryptedContent = \Illuminate\Support\Facades\Storage::get($path);

            // Decrypt the content
            $decryptedContent = \Illuminate\Support\Facades\Crypt::decrypt($encryptedContent);

            // Log the access
            Log::info('Encrypted file accessed', [
                'file_path' => $path,
                'user_id' => $userId,
                'timestamp' => now()
            ]);

            return $decryptedContent;

        } catch (\Exception $e) {
            Log::error('Failed to retrieve encrypted file', [
                'file_path' => $path,
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);

            throw new \Exception('Failed to retrieve encrypted file: ' . $e->getMessage());
        }
    }

    /**
     * Store encrypted file and return download response
     */
    public function downloadEncryptedFile(string $path, string $originalName, ?string $userId = null): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $decryptedContent = $this->retrieveEncryptedFile($path, $userId);

            // Determine MIME type from file extension
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $mimeType = $this->getMimeTypeFromExtension($extension);

            return response($decryptedContent)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'attachment; filename="' . $originalName . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            Log::error('Failed to download encrypted file', [
                'file_path' => $path,
                'original_name' => $originalName,
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);

            throw new \Exception('Failed to download encrypted file: ' . $e->getMessage());
        }
    }

    /**
     * Delete encrypted file securely
     */
    public function deleteEncryptedFile(string $path, ?string $userId = null): bool
    {
        try {
            if (!\Illuminate\Support\Facades\Storage::exists($path)) {
                return true; // File doesn't exist, consider it deleted
            }

            // Delete the file
            $deleted = \Illuminate\Support\Facades\Storage::delete($path);

            // Log the deletion
            Log::info('Encrypted file deleted', [
                'file_path' => $path,
                'user_id' => $userId,
                'success' => $deleted,
                'timestamp' => now()
            ]);

            return $deleted;

        } catch (\Exception $e) {
            Log::error('Failed to delete encrypted file', [
                'file_path' => $path,
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);

            return false;
        }
    }

    /**
     * Get MIME type from file extension
     */
    private function getMimeTypeFromExtension(string $extension): string
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls' => 'application/vnd.ms-excel'
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }

    /**
     * Validate file integrity using stored hash
     */
    public function validateFileIntegrity(string $path, string $expectedHash, ?string $userId = null): bool
    {
        try {
            if (!\Illuminate\Support\Facades\Storage::exists($path)) {
                return false;
            }

            // Get file content and calculate hash
            $content = \Illuminate\Support\Facades\Storage::get($path);
            $actualHash = hash('sha256', $content);

            $isValid = hash_equals($expectedHash, $actualHash);

            // Log integrity check
            Log::info('File integrity check performed', [
                'file_path' => $path,
                'user_id' => $userId,
                'is_valid' => $isValid,
                'timestamp' => now()
            ]);

            if (!$isValid) {
                Log::warning('File integrity check failed', [
                    'file_path' => $path,
                    'expected_hash' => $expectedHash,
                    'actual_hash' => $actualHash,
                    'user_id' => $userId
                ]);
            }

            return $isValid;

        } catch (\Exception $e) {
            Log::error('File integrity check error', [
                'file_path' => $path,
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);

            return false;
        }
    }
}
