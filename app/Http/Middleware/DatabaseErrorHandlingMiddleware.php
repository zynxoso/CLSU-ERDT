<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;
use PDOException;
use Throwable;

/**
 * Middleware para sa database error handling at connection monitoring.
 * 
 * Ang middleware na ito ay nag-handle ng database-related errors
 * at nag-monitor ng database connection health.
 */
class DatabaseErrorHandlingMiddleware
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
            // Check database connection before processing request
            $this->checkDatabaseConnection();
            
            // Process the request
            $response = $next($request);
            
            return $response;
            
        } catch (QueryException $e) {
            return $this->handleDatabaseError($e, $request);
        } catch (PDOException $e) {
            return $this->handlePDOError($e, $request);
        } catch (Throwable $e) {
            // Re-throw non-database errors to be handled by other middleware
            throw $e;
        }
    }
    
    /**
     * Check if database connection is healthy.
     */
    private function checkDatabaseConnection(): void
    {
        try {
            DB::connection()->getPdo();
            
            // Test with a simple query
            DB::select('SELECT 1');
            
        } catch (Throwable $e) {
            Log::critical('Database connection failed', [
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ]);
            
            throw new \App\Exceptions\ServerErrorException(
                'Database connection unavailable',
                503,
                $e,
                ['connection_test' => 'failed']
            );
        }
    }
    
    /**
     * Handle database query exceptions.
     */
    private function handleDatabaseError(QueryException $e, Request $request): Response
    {
        $errorCode = $e->errorInfo[1] ?? null;
        $sqlState = $e->errorInfo[0] ?? null;
        
        // Log the database error
        Log::error('Database query error', [
            'sql_state' => $sqlState,
            'error_code' => $errorCode,
            'message' => $e->getMessage(),
            'sql' => $e->getSql(),
            'bindings' => $e->getBindings(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => auth()->id()
        ]);
        
        // Handle specific database errors
        $response = $this->handleSpecificDatabaseError($errorCode, $sqlState, $e, $request);
        
        if ($response) {
            return $response;
        }
        
        // Default database error handling
        return $this->createErrorResponse(
            'A database error occurred. Please try again.',
            500,
            $request
        );
    }
    
    /**
     * Handle PDO exceptions.
     */
    private function handlePDOError(PDOException $e, Request $request): Response
    {
        Log::error('PDO error', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => auth()->id()
        ]);
        
        // Check if it's a connection error
        if (str_contains($e->getMessage(), 'Connection refused') || 
            str_contains($e->getMessage(), 'server has gone away')) {
            return $this->createErrorResponse(
                'Database connection lost. Please try again.',
                503,
                $request
            );
        }
        
        return $this->createErrorResponse(
            'A database connection error occurred. Please try again.',
            500,
            $request
        );
    }
    
    /**
     * Handle specific database error codes.
     */
    private function handleSpecificDatabaseError($errorCode, $sqlState, QueryException $e, Request $request): ?Response
    {
        switch ($errorCode) {
            case 1062: // Duplicate entry
                return $this->createErrorResponse(
                    'This record already exists. Please check your data.',
                    422,
                    $request
                );
                
            case 1452: // Foreign key constraint fails
                return $this->createErrorResponse(
                    'Invalid reference data. Please check your selection.',
                    422,
                    $request
                );
                
            case 1451: // Cannot delete or update a parent row
                return $this->createErrorResponse(
                    'Cannot delete this record because it is being used by other data.',
                    422,
                    $request
                );
                
            case 1054: // Unknown column
                Log::critical('Database schema error - unknown column', [
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings(),
                    'error' => $e->getMessage()
                ]);
                
                return $this->createErrorResponse(
                    'A system error occurred. Please contact support.',
                    500,
                    $request
                );
                
            case 1146: // Table doesn't exist
                Log::critical('Database schema error - table missing', [
                    'sql' => $e->getSql(),
                    'error' => $e->getMessage()
                ]);
                
                return $this->createErrorResponse(
                    'A system error occurred. Please contact support.',
                    500,
                    $request
                );
                
            case 2006: // MySQL server has gone away
                return $this->createErrorResponse(
                    'Database connection lost. Please try again.',
                    503,
                    $request
                );
                
            case 1205: // Lock wait timeout
                return $this->createErrorResponse(
                    'The system is busy. Please try again in a moment.',
                    503,
                    $request
                );
                
            case 1213: // Deadlock found
                return $this->createErrorResponse(
                    'A temporary conflict occurred. Please try again.',
                    503,
                    $request
                );
        }
        
        // Handle SQL states
        switch ($sqlState) {
            case '08S01': // Communication link failure
            case '08003': // Connection does not exist
            case '08006': // Connection failure
                return $this->createErrorResponse(
                    'Database connection error. Please try again.',
                    503,
                    $request
                );
                
            case '42S02': // Base table or view not found
                Log::critical('Database schema error - missing table/view', [
                    'sql_state' => $sqlState,
                    'error' => $e->getMessage()
                ]);
                
                return $this->createErrorResponse(
                    'A system error occurred. Please contact support.',
                    500,
                    $request
                );
        }
        
        return null;
    }
    
    /**
     * Create appropriate error response based on request type.
     */
    private function createErrorResponse(string $message, int $statusCode, Request $request): Response
    {
        // For API requests, return JSON response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'error_code' => 'DATABASE_ERROR',
                'timestamp' => now()->toISOString()
            ], $statusCode);
        }
        
        // For web requests, redirect back with error
        if ($statusCode >= 500) {
            // For server errors, redirect to error page
            return response()->view('errors.500', [
                'message' => $message,
                'statusCode' => $statusCode
            ], $statusCode);
        }
        
        // For client errors, redirect back with error message
        return back()->with('error', $message)->withInput();
    }
}