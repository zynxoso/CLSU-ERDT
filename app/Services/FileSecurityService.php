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
     * Validate file signature (magic numbers)
     */
    private function validateFileSignature(UploadedFile $file, array &$result): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!array_key_exists($extension, self::FILE_SIGNATURES)) {
            $result['warnings'][] = "No signature validation available for '{$extension}' files";
            return;
        }

        $fileContent = file_get_contents($file->getRealPath(), false, null, 0, 1024);
        $signatures = self::FILE_SIGNATURES[$extension];
        $signatureFound = false;

        foreach ($signatures as $signature) {
            if (strpos($fileContent, $signature) === 0) {
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
}