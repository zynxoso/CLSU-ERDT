<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
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
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Custom reporting logic can be added here
        });

        // Handle all exceptions for API requests
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->handleApiException($e, $request);
            }
        });

        // Handle web route exceptions with custom error pages
        $this->renderable(function (Throwable $e, Request $request) {
            if (!$request->expectsJson() && !$request->is('api/*')) {
                return $this->handleWebException($e, $request);
            }
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

        // Handle custom validation exceptions
        if ($e instanceof CustomValidationException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
                'error_code' => $e->getErrorCode(),
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
        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        // Map common exceptions to appropriate status codes
        return match (true) {
            $e instanceof ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            $e instanceof \App\Exceptions\CustomValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            $e instanceof \App\Exceptions\NotFoundException => Response::HTTP_NOT_FOUND,
            $e instanceof \App\Exceptions\UnauthorizedException => Response::HTTP_UNAUTHORIZED,
            $e instanceof \App\Exceptions\ForbiddenException => Response::HTTP_FORBIDDEN,
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
        if (method_exists($e, 'getErrorCode')) {
            return $e->getErrorCode();
        }

        // Generate an error code based on the exception class
        $className = get_class($e);
        $shortName = substr($className, strrpos($className, '\\') + 1);
        return strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $shortName));
    }

    /**
     * Determine if the exception should be reported (details shown).
     */
    private function shouldReportException(Throwable $e): bool
    {
        return !app()->environment('production') || config('app.debug', false);
    }
}
