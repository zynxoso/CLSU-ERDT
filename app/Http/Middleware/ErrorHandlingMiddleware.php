<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Middleware para sa comprehensive error handling at logging.
 * 
 * Ang middleware na ito ay tumutulong sa pag-handle ng mga error
 * bago pa man ito makarating sa main exception handler.
 */
class ErrorHandlingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Log request details for debugging
            $this->logRequestDetails($request);
            
            // Validate request size and content
            $this->validateRequest($request);
            
            // Process the request
            $response = $next($request);
            
            // Log response details if needed
            $this->logResponseDetails($request, $response);
            
            return $response;
            
        } catch (Throwable $e) {
            // Log the error with context
            $this->logError($e, $request);
            
            // Handle the error based on request type
            return $this->handleError($e, $request);
        }
    }
    
    /**
     * Log request details for debugging purposes.
     */
    private function logRequestDetails(Request $request): void
    {
        // Only log in debug mode to avoid excessive logging
        if (!config('app.debug')) {
            return;
        }
        
        Log::debug('Request processed', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Validate incoming request for common issues.
     */
    private function validateRequest(Request $request): void
    {
        // Check for oversized requests
        $maxSize = config('app.max_request_size', 50 * 1024 * 1024); // 50MB default
        if ($request->server('CONTENT_LENGTH') > $maxSize) {
            throw new \App\Exceptions\BadRequestException(
                'Request size exceeds maximum allowed limit',
                413,
                null,
                ['max_size' => $maxSize, 'actual_size' => $request->server('CONTENT_LENGTH')]
            );
        }
        
        // Check for suspicious request patterns
        $this->checkSuspiciousPatterns($request);
    }
    
    /**
     * Check for suspicious request patterns that might indicate attacks.
     */
    private function checkSuspiciousPatterns(Request $request): void
    {
        $suspiciousPatterns = [
            '/\.\.\//i',  // Directory traversal
            '/<script/i',  // XSS attempts
            '/union.*select/i',  // SQL injection
            '/exec\(/i',  // Code execution
            '/eval\(/i',  // Code evaluation
        ];
        
        $requestData = json_encode($request->all());
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $requestData)) {
                Log::warning('Suspicious request pattern detected', [
                    'pattern' => $pattern,
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'user_id' => Auth::id()
                ]);
                
                throw new \App\Exceptions\BadRequestException(
                    'Invalid request data detected',
                    400,
                    null,
                    ['pattern_matched' => $pattern]
                );
            }
        }
    }
    
    /**
     * Log response details if there are issues.
     */
    private function logResponseDetails(Request $request, Response $response): void
    {
        // Log slow responses
        if (defined('LARAVEL_START')) {
            $executionTime = microtime(true) - LARAVEL_START;
            if ($executionTime > 5.0) { // Log requests taking more than 5 seconds
                Log::warning('Slow response detected', [
                    'execution_time' => $executionTime,
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'status_code' => $response->getStatusCode()
                ]);
            }
        }
        
        // Log error responses
        if ($response->getStatusCode() >= 400) {
            Log::info('Error response', [
                'status_code' => $response->getStatusCode(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => Auth::id()
            ]);
        }
    }
    
    /**
     * Log error details with comprehensive context.
     */
    private function logError(Throwable $e, Request $request): void
    {
        Log::error('Middleware caught exception', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
            'request_data' => $request->except(['password', 'password_confirmation', '_token']),
            'stack_trace' => $e->getTraceAsString()
        ]);
    }
    
    /**
     * Handle the error based on request type and return appropriate response.
     */
    private function handleError(Throwable $e, Request $request): Response
    {
        // For API requests, return JSON error response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $this->getErrorMessage($e),
                'error_code' => $this->getErrorCode($e),
                'timestamp' => now()->toISOString()
            ], $this->getStatusCode($e));
        }
        
        // For web requests, redirect with error message
        $message = $this->getErrorMessage($e);
        
        // If it's a validation error, redirect back with errors
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return back()->withErrors($e->errors())->withInput();
        }
        
        // For other errors, redirect back with error message
        return back()->with('error', $message);
    }
    
    /**
     * Get appropriate error message based on environment and exception type.
     */
    private function getErrorMessage(Throwable $e): string
    {
        // In production, hide detailed error messages for security
        if (app()->environment('production') && !($e instanceof \App\Exceptions\BaseException)) {
            return 'An unexpected error occurred. Please try again.';
        }
        
        return $e->getMessage() ?: 'An unexpected error occurred.';
    }
    
    /**
     * Get error code from exception.
     */
    private function getErrorCode(Throwable $e): string
    {
        if (method_exists($e, 'getErrorCode')) {
            return $e->getCode() ?: 'UNKNOWN_ERROR';
        }
        
        $className = get_class($e);
        $shortName = substr($className, strrpos($className, '\\') + 1);
        return strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $shortName));
    }
    
    /**
     * Get appropriate HTTP status code for the exception.
     */
    private function getStatusCode(Throwable $e): int
    {
        if (method_exists($e, 'getStatusCode')) {
            return $e->getCode() ?: 500;
        }
        
        return match (true) {
            $e instanceof \Illuminate\Validation\ValidationException => 422,
            $e instanceof \App\Exceptions\BadRequestException => 400,
            default => 500
        };
    }
}