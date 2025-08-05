<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class FileManagementService
{
    public function uploadFile(UploadedFile $file, string $directory, array $allowedMimes = [], int $maxSize = 10240): array
    {
        // Validate file
        $this->validateFile($file, $allowedMimes, $maxSize);

        // Generate unique filename
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        
        // Ensure directory exists
        $fullPath = public_path($directory);
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        // Move file
        $file->move($fullPath, $filename);
        
        return [
            'filename' => $originalName,
            'file_path' => $directory . '/' . $filename,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ];
    }

    public function deleteFile(string $filePath): bool
    {
        try {
            $fullPath = public_path($filePath);
            if (file_exists($fullPath)) {
                return unlink($fullPath);
            }
            return true; // File doesn't exist, consider it deleted
        } catch (Exception $e) {
            throw new Exception("Failed to delete file: " . $e->getMessage());
        }
    }

    public function replaceFile(UploadedFile $newFile, string $oldFilePath, string $directory, array $allowedMimes = [], int $maxSize = 10240): array
    {
        // Upload new file first
        $fileData = $this->uploadFile($newFile, $directory, $allowedMimes, $maxSize);
        
        // Delete old file if upload was successful
        if ($oldFilePath) {
            $this->deleteFile($oldFilePath);
        }
        
        return $fileData;
    }

    public function validateFile(UploadedFile $file, array $allowedMimes = [], int $maxSize = 10240): void
    {
        // Check file size (in KB)
        if ($file->getSize() > ($maxSize * 1024)) {
            throw new Exception("File size exceeds maximum allowed size of {$maxSize}KB");
        }

        // Check MIME type if specified
        if (!empty($allowedMimes) && !in_array($file->getMimeType(), $this->getMimeTypes($allowedMimes))) {
            throw new Exception("File type not allowed. Allowed types: " . implode(', ', $allowedMimes));
        }
    }

    public function getFileSize(string $filePath): ?int
    {
        $fullPath = public_path($filePath);
        return file_exists($fullPath) ? filesize($fullPath) : null;
    }

    public function fileExists(string $filePath): bool
    {
        return file_exists(public_path($filePath));
    }

    private function getMimeTypes(array $extensions): array
    {
        $mimeMap = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];

        $mimes = [];
        foreach ($extensions as $ext) {
            if (isset($mimeMap[$ext])) {
                $mimes[] = $mimeMap[$ext];
            }
        }

        return $mimes;
    }

    public function getImageMimes(): array
    {
        return $this->getMimeTypes(['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    public function getDocumentMimes(): array
    {
        return $this->getMimeTypes(['pdf', 'doc', 'docx', 'xls', 'xlsx']);
    }
}