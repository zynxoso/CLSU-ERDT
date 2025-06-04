<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\FileSecurityService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CyberSweepMiddleware
{
    protected FileSecurityService $fileSecurityService;

    public function __construct(FileSecurityService $fileSecurityService)
    {
        $this->fileSecurityService = $fileSecurityService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip cybersweep for GET requests as they don't modify data
        if ($request->isMethod('GET')) {
            return $next($request);
        }

        // Scan for suspicious patterns in request data
        $this->scanRequestData($request);

        // Enhanced file scanning with FileSecurityService
        if ($request->hasFile('document') || $request->hasFile('file')) {
            $fileValidation = $this->scanUploadedFilesAdvanced($request);
            
            // Block request if critical security issues found
            if (!$fileValidation['valid'] && $fileValidation['critical']) {
                Log::critical('File upload blocked due to security threats', [
                    'ip' => $request->ip(),
                    'user_id' => $request->user() ? $request->user()->id : 'guest',
                    'errors' => $fileValidation['errors']
                ]);
                
                abort(400, 'File upload blocked due to security policy violations');
            }
        }

        // Continue with the request if no critical issues found
        return $next($request);
    }

    /**
     * Scan request data for suspicious patterns
     */
    private function scanRequestData(Request $request): void
    {
        $data = $request->all();
        $suspiciousPatterns = [
            // SQL Injection patterns
            '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i',
            '/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i',
            '/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i',
            '/(union).*(select)/i',

            // XSS patterns
            '/<script[^>]*>.*?<\/script>/i',
            '/javascript:[^"]*/i',
            '/on\w+\s*=\s*["\'][^"\']*["\']/',

            // Path traversal
            '/\.\.\//i',

            // Command injection
            '/;.*\$\(.*\)/',
            '/`.*`/'
        ];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                foreach ($suspiciousPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        // Log the suspicious activity
                        Log::warning('CyberSweep: Suspicious pattern detected', [
                            'pattern' => $pattern,
                            'field' => $key,
                            'ip' => $request->ip(),
                            'user_id' => $request->user() ? $request->user()->id : 'guest',
                            'uri' => $request->getRequestUri()
                        ]);

                        // For now, we're just logging. In a production environment,
                        // you might want to block the request or sanitize the input.
                    }
                }
            }
        }
    }

    /**
     * Advanced file scanning using FileSecurityService
     */
    private function scanUploadedFilesAdvanced(Request $request): array
    {
        $files = [];
        if ($request->hasFile('document')) {
            $files[] = $request->file('document');
        }
        if ($request->hasFile('file')) {
            $files[] = $request->file('file');
        }

        $overallResult = [
            'valid' => true,
            'critical' => false,
            'errors' => [],
            'warnings' => []
        ];

        foreach ($files as $file) {
            $userId = $request->user() ? (string)$request->user()->id : null;
            $validation = $this->fileSecurityService->validateFile($file, $userId);
            
            if (!$validation['valid']) {
                $overallResult['valid'] = false;
                $overallResult['errors'] = array_merge($overallResult['errors'], $validation['errors']);
                
                // Determine if errors are critical (should block upload)
                $criticalPatterns = [
                    'suspicious content patterns',
                    'signature does not match',
                    'path traversal',
                    'invalid characters'
                ];
                
                foreach ($validation['errors'] as $error) {
                    foreach ($criticalPatterns as $pattern) {
                        if (stripos($error, $pattern) !== false) {
                            $overallResult['critical'] = true;
                            
                            // Quarantine the suspicious file
                            $this->fileSecurityService->quarantineFile($file, $error);
                            break 2;
                        }
                    }
                }
            }
            
            $overallResult['warnings'] = array_merge($overallResult['warnings'], $validation['warnings']);
        }

        return $overallResult;
    }

    /**
     * Legacy file scanning method (kept for backward compatibility)
     */
    private function scanUploadedFiles(Request $request): void
    {
        // This method is now deprecated in favor of scanUploadedFilesAdvanced
        // Keeping for backward compatibility
        $this->scanUploadedFilesAdvanced($request);
    }
}
