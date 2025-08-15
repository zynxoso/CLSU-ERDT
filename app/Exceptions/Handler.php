<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Illuminate\Session\TokenMismatchException;
use PDOException;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'token',
        'secret',
        'api_key',
        'access_token',
        'refresh_token',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Enhanced reportable callback with detailed logging
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReportException($e)) {
                $this->logExceptionWithContext($e);
            }
        });

        // Enhanced renderable callback with comprehensive error handling
        $this->renderable(function (Throwable $e, Request $request) {
            // Handle API requests
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->handleApiException($e, $request);
            }

            // Handle web requests
            return $this->handleWebException($e, $request);
        });

        // Specific exception handlers
        $this->renderable(function (ValidationException $e, Request $request) {
            return $this->handleValidationException($e, $request);
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            return $this->handleAuthenticationException($e, $request);
        });

        $this->renderable(function (AuthorizationException $e, Request $request) {
            return $this->handleAuthorizationException($e, $request);
        });

        $this->renderable(function (QueryException $e, Request $request) {
            return $this->handleDatabaseException($e, $request);
        });

        $this->renderable(function (TokenMismatchException $e, Request $request) {
            return $this->handleTokenMismatchException($e, $request);
        });

        $this->renderable(function (TooManyRequestsHttpException $e, Request $request) {
            return $this->handleRateLimitException($e, $request);
        });

        $this->renderable(function (ServiceUnavailableHttpException $e, Request $request) {
            return $this->handleServiceUnavailableException($e, $request);
        });
    }

    /**
     * Handle web exceptions and return standardized error pages.
     */
    private function handleWebException(Throwable $e, Request $request)
    {
        $statusCode = $this->getStatusCode($e);

        // Check if we have a custom error view for this status code
        $view = "errors.{$statusCode}";

        if (!View::exists($view)) {
            $view = 'errors.generic';

            // If generic error view doesn't exist, fall back to Laravel's default error handling
            if (!View::exists($view)) {
                return null;
            }
        }

        return response()->view($view, [
            'exception' => $e,
            'statusCode' => $statusCode,
            'message' => $this->getErrorMessage($e, $statusCode),
            'errorCode' => $this->getErrorCode($e),
        ], $statusCode);
    }

    /**
     * Handle API exceptions and return standardized JSON responses.
     */
    private function handleApiException(Throwable $e, Request $request): JsonResponse
    {
        $statusCode = $this->getStatusCode($e);

        // Handle Laravel validation exceptions
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->validator->errors()->toArray(),
            ], $statusCode);
        }



        // Handle all other exceptions
        return response()->json([
            'success' => false,
            'message' => $this->getErrorMessage($e, $statusCode),
            'error_code' => $this->getErrorCode($e),
            'exception' => $this->shouldReportException($e) ? get_class($e) : null,
        ], $statusCode);
    }

    /**
     * Get appropriate status code for the exception.
     */
    private function getStatusCode(Throwable $e): int
    {
        // Get status code from exception if it's an HTTP exception
        if ($e instanceof HttpException) {
            return $e->getStatusCode();
        }

        // Map common exceptions to appropriate status codes
        return match (true) {
            $e instanceof ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            $e instanceof \App\Exceptions\BadRequestException => Response::HTTP_BAD_REQUEST,
            $e instanceof \App\Exceptions\ServerErrorException => Response::HTTP_INTERNAL_SERVER_ERROR,
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    /**
     * Get appropriate error message based on the exception and environment.
     */
    private function getErrorMessage(Throwable $e, int $statusCode): string
    {
        // In production, hide detailed error messages for server errors
        if ($statusCode >= 500 && app()->environment('production')) {
            return 'Server Error';
        }

        // For custom exceptions, use their message
        if ($e instanceof \App\Exceptions\BaseException) {
            return $e->getMessage();
        }

        // For other exceptions, use the exception message or a default one
        return $e->getMessage() ?: 'An unexpected error occurred';
    }

    /**
     * Get error code from exception if available.
     */
    private function getErrorCode(Throwable $e): string
    {
        // Check if exception has a custom error code method
        if (method_exists($e, 'getCode') && $e->getCode()) {
            return (string) $e->getCode();
        }

        // Generate an error code based on the exception class
        $className = get_class($e);
        $shortName = substr($className, strrpos($className, '\\') + 1);
        return strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $shortName));
    }

    /**
     * Log exception with detailed context.
     */
    private function logExceptionWithContext(Throwable $e): void
    {
        $context = [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'timestamp' => now()->toISOString()
        ];

        // Add request data for non-sensitive requests
        if (!$this->containsSensitiveData(request())) {
            $context['request_data'] = request()->except($this->dontFlash);
        }

        Log::error('Exception occurred', $context);
    }

    /**
     * Handle validation exceptions.
     */
    private function handleValidationException(ValidationException $e, Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        }

        // For web requests, let Laravel handle it normally
        return null;
    }

    /**
     * Handle authentication exceptions.
     */
    private function handleAuthenticationException(AuthenticationException $e, Request $request)
    {
        // Determine correct login route based on guard or route context
        $guards = method_exists($e, 'guards') ? $e->guards() : [];
        $isScholarContext = in_array('scholar', $guards, true)
            || $request->is('scholar/*')
            || ($request->routeIs('scholar.*') ?? false);
        $loginRouteName = $isScholarContext ? 'scholar.login' : 'login';

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
                'error_code' => 'AUTH_REQUIRED',
                'redirect_url' => route($loginRouteName)
            ], 401);
        }

        return redirect()->guest(route($loginRouteName))
            ->with('error', 'Please log in to access this page.');
    }

    /**
     * Handle authorization exceptions.
     */
    private function handleAuthorizationException(AuthorizationException $e, Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action',
                'error_code' => 'AUTHORIZATION_ERROR'
            ], 403);
        }

        return response()->view('errors.403', [
            'exception' => $e,
            'message' => $e->getMessage() ?: 'You are not authorized to access this resource.'
        ], 403);
    }

    /**
     * Handle database exceptions.
     */
    private function handleDatabaseException(QueryException $e, Request $request)
    {
        $message = $this->getDatabaseErrorMessage($e);
        $statusCode = $this->getDatabaseErrorStatusCode($e);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'error_code' => 'DATABASE_ERROR'
            ], $statusCode);
        }

        return response()->view('errors.500', [
            'exception' => $e,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Handle token mismatch exceptions.
     */
    private function handleTokenMismatchException(TokenMismatchException $e, Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'CSRF token mismatch. Please refresh the page and try again.',
                'error_code' => 'CSRF_MISMATCH',
                'action' => 'refresh_page'
            ], 419);
        }

        return redirect()->back()
            ->withInput($request->except($this->dontFlash))
            ->with('error', 'Your session has expired. Please try again.');
    }

    /**
     * Handle rate limit exceptions.
     */
    private function handleRateLimitException(TooManyRequestsHttpException $e, Request $request)
    {
        $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
                'error_code' => 'RATE_LIMIT_EXCEEDED',
                'retry_after' => $retryAfter
            ], 429)->header('Retry-After', $retryAfter);
        }

        return response()->view('errors.429', [
            'exception' => $e,
            'retryAfter' => $retryAfter
        ], 429)->header('Retry-After', $retryAfter);
    }

    /**
     * Handle service unavailable exceptions.
     */
    private function handleServiceUnavailableException(ServiceUnavailableHttpException $e, Request $request)
    {
        $retryAfter = $e->getHeaders()['Retry-After'] ?? 300;

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Service temporarily unavailable',
                'error_code' => 'SERVICE_UNAVAILABLE',
                'retry_after' => $retryAfter
            ], 503)->header('Retry-After', $retryAfter);
        }

        return response()->view('errors.503', [
            'exception' => $e,
            'retryAfter' => $retryAfter
        ], 503)->header('Retry-After', $retryAfter);
    }

    /**
     * Get database error message.
     */
    private function getDatabaseErrorMessage(QueryException $e): string
    {
        if (app()->environment('production')) {
            $errorCode = $e->errorInfo[1] ?? null;

            switch ($errorCode) {
                case 1062:
                    return 'This record already exists. Please check your data.';
                case 1452:
                    return 'Invalid reference data. Please check your selection.';
                case 1451:
                    return 'Cannot delete this record because it is being used by other data.';
                case 2006:
                    return 'Database connection lost. Please try again.';
                default:
                    return 'A database error occurred. Please try again.';
            }
        }

        return $e->getMessage();
    }

    /**
     * Get database error status code.
     */
    private function getDatabaseErrorStatusCode(QueryException $e): int
    {
        $errorCode = $e->errorInfo[1] ?? null;

        switch ($errorCode) {
            case 1062: // Duplicate entry
                return 409;
            case 1452: // Foreign key constraint
            case 1451: // Cannot delete
                return 422;
            case 2006: // Connection lost
                return 503;
            default:
                return 500;
        }
    }

    /**
     * Check if request contains sensitive data.
     */
    private function containsSensitiveData(Request $request): bool
    {
        $sensitiveFields = array_merge($this->dontFlash, [
            'credit_card',
            'ssn',
            'social_security',
            'bank_account',
            'routing_number'
        ]);

        foreach ($sensitiveFields as $field) {
            if ($request->has($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the exception should be reported.
     */
    private function shouldReportException(Throwable $e): bool
    {
        // Don't report certain exceptions in production
        if (app()->environment('production')) {
            $dontReport = [
                ValidationException::class,
                AuthenticationException::class,
                AuthorizationException::class,
                NotFoundHttpException::class,
                MethodNotAllowedHttpException::class,
                TokenMismatchException::class,
            ];

            foreach ($dontReport as $type) {
                if ($e instanceof $type) {
                    return false;
                }
            }
        }

        return true;
    }
}

