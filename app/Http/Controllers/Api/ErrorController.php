<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * API Error Controller
 * 
 * Handles API error responses and client-side error logging.
 */
class ErrorController extends Controller
{
    /**
     * Log client-side errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logClientError(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'type' => 'required|string|in:javascript,promise_rejection,resource,livewire,network',
                'message' => 'required|string|max:1000',
                'filename' => 'nullable|string|max:500',
                'line' => 'nullable|integer',
                'column' => 'nullable|integer',
                'stack' => 'nullable|string|max:5000',
                'element' => 'nullable|string|max:100',
                'src' => 'nullable|string|max:500',
                'component' => 'nullable|string|max:200',
                'timestamp' => 'required|string'
            ]);

            // Log the client error
            Log::channel('client-errors')->error('Client-side error', [
                'error_data' => $validated,
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->header('referer'),
                'session_id' => session()->getId(),
                'logged_at' => now()->toISOString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Error logged successfully'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid error data',
                'errors' => $e->errors()
            ], 422);

        } catch (Throwable $e) {
            Log::error('Failed to log client error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to log error'
            ], 500);
        }
    }

    /**
     * Handle API validation errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleValidationError(Request $request): JsonResponse
    {
        $errors = session('errors') ? session('errors')->toArray() : [];
        
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors,
            'error_code' => 'VALIDATION_ERROR'
        ], 422);
    }

    /**
     * Handle API authentication errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleAuthError(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Authentication required',
            'error_code' => 'AUTH_REQUIRED',
            'redirect_url' => route('login')
        ], 401);
    }

    /**
     * Handle API authorization errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleAuthorizationError(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'You are not authorized to perform this action',
            'error_code' => 'AUTHORIZATION_ERROR'
        ], 403);
    }

    /**
     * Handle API not found errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleNotFoundError(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'The requested resource was not found',
            'error_code' => 'NOT_FOUND'
        ], 404);
    }

    /**
     * Handle API method not allowed errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleMethodNotAllowed(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not allowed for this endpoint',
            'error_code' => 'METHOD_NOT_ALLOWED',
            'allowed_methods' => $request->route() ? $request->route()->methods() : []
        ], 405);
    }

    /**
     * Handle API rate limit errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleRateLimitError(Request $request): JsonResponse
    {
        $retryAfter = $request->header('Retry-After', 60);
        
        return response()->json([
            'success' => false,
            'message' => 'Too many requests. Please try again later.',
            'error_code' => 'RATE_LIMIT_EXCEEDED',
            'retry_after' => $retryAfter
        ], 429)->header('Retry-After', $retryAfter);
    }

    /**
     * Handle API server errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleServerError(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'An internal server error occurred',
            'error_code' => 'SERVER_ERROR'
        ], 500);
    }

    /**
     * Handle API service unavailable errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleServiceUnavailable(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Service temporarily unavailable',
            'error_code' => 'SERVICE_UNAVAILABLE',
            'retry_after' => 300 // 5 minutes
        ], 503)->header('Retry-After', 300);
    }

    /**
     * Handle database connection errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleDatabaseError(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Database connection error. Please try again.',
            'error_code' => 'DATABASE_ERROR'
        ], 503);
    }

    /**
     * Handle CSRF token mismatch errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleCSRFError(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'CSRF token mismatch. Please refresh the page and try again.',
            'error_code' => 'CSRF_MISMATCH',
            'action' => 'refresh_page'
        ], 419);
    }

    /**
     * Handle maintenance mode errors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleMaintenanceMode(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Application is currently under maintenance',
            'error_code' => 'MAINTENANCE_MODE',
            'retry_after' => 3600 // 1 hour
        ], 503)->header('Retry-After', 3600);
    }

    /**
     * Get error health status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function healthCheck(Request $request): JsonResponse
    {
        try {
            // Check database connection
            DB::connection()->getPdo();
            $dbStatus = 'healthy';
        } catch (Throwable $e) {
            $dbStatus = 'unhealthy';
        }

        // Check cache
        try {
            cache()->put('health_check', 'test', 1);
            $cacheValue = cache()->get('health_check');
            $cacheStatus = $cacheValue === 'test' ? 'healthy' : 'unhealthy';
        } catch (Throwable $e) {
            $cacheStatus = 'unhealthy';
        }

        // Check session
        try {
            session()->put('health_check', 'test');
            $sessionValue = session()->get('health_check');
            $sessionStatus = $sessionValue === 'test' ? 'healthy' : 'unhealthy';
        } catch (Throwable $e) {
            $sessionStatus = 'unhealthy';
        }

        $overallStatus = ($dbStatus === 'healthy' && $cacheStatus === 'healthy' && $sessionStatus === 'healthy') 
            ? 'healthy' : 'unhealthy';

        $statusCode = $overallStatus === 'healthy' ? 200 : 503;

        return response()->json([
            'status' => $overallStatus,
            'timestamp' => now()->toISOString(),
            'services' => [
                'database' => $dbStatus,
                'cache' => $cacheStatus,
                'session' => $sessionStatus
            ],
            'version' => config('app.version', '1.0.0')
        ], $statusCode);
    }

    /**
     * Get error statistics.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getErrorStats(Request $request): JsonResponse
    {
        try {
            // This would typically come from a monitoring service or database
            $stats = [
                'total_errors_24h' => 0,
                'error_rate' => 0.0,
                'most_common_errors' => [],
                'system_health' => 'good',
                'last_updated' => now()->toISOString()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve error statistics'
            ], 500);
        }
    }

    /**
     * Test error handling.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testError(Request $request): JsonResponse
    {
        if (!app()->environment('local', 'testing')) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing is only available in development environments'
            ], 403);
        }

        $errorType = $request->get('type', 'general');

        switch ($errorType) {
            case 'validation':
                throw ValidationException::withMessages([
                    'test_field' => ['This is a test validation error']
                ]);

            case 'database':
                DB::statement('SELECT * FROM non_existent_table');
                break;

            case 'authorization':
                abort(403, 'Test authorization error');
                break;

            case 'not_found':
                abort(404, 'Test not found error');
                break;

            case 'server':
                throw new \Exception('Test server error');

            default:
                throw new \Exception('Test general error');
        }

        return response()->json([
            'success' => true,
            'message' => 'No error occurred'
        ]);
    }
}