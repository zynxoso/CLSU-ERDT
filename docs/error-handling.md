# Error Handling Guide

This document provides guidelines for handling errors in the CLSU-ERDT Scholar Management System.

## Table of Contents

1. [Introduction](#introduction)
2. [Global Exception Handler](#global-exception-handler)
3. [Custom Exception Classes](#custom-exception-classes)
4. [Using Custom Exceptions](#using-custom-exceptions)
5. [Error Response Format](#error-response-format)
6. [Error Pages](#error-pages)
7. [Testing Error Handling](#testing-error-handling)

## Introduction

Proper error handling is essential for creating a robust and user-friendly application. The CLSU-ERDT Scholar Management System implements a standardized approach to error handling that:

- Provides consistent error responses across the application
- Makes debugging easier
- Improves user experience by showing appropriate error messages
- Secures the application by hiding sensitive error details in production

## Global Exception Handler

The application uses a global exception handler (`App\Exceptions\Handler`) that catches all exceptions thrown in the application and formats them appropriately based on the request type (API or web).

### Key Features

- Standardized JSON responses for API requests
- Custom error pages for web requests
- Environment-aware error details (more details in development, less in production)
- Custom error codes for easier debugging

## Custom Exception Classes

The application provides several custom exception classes for different types of errors:

| Exception Class | HTTP Status | Use Case |
|-----------------|-------------|----------|
| `BaseException` | Varies | Base class for all custom exceptions |
| `BadRequestException` | 400 | Invalid request data |
| `UnauthorizedException` | 401 | Authentication required |
| `ForbiddenException` | 403 | Permission denied |
| `NotFoundException` | 404 | Resource not found |
| `CustomValidationException` | 422 | Validation errors |
| `ServerErrorException` | 500 | Internal server errors |

All custom exceptions extend the `BaseException` class, which provides:

- Custom error codes
- Context data for additional error information
- Consistent error message formatting

## Using Custom Exceptions

### Basic Usage

```php
use App\Exceptions\NotFoundException;

public function show($id)
{
    $user = User::find($id);
    
    if (!$user) {
        throw new NotFoundException("User with ID {$id} not found");
    }
    
    return view('users.show', compact('user'));
}
```

### With Context Data

```php
use App\Exceptions\ForbiddenException;

public function update($id)
{
    $resource = Resource::find($id);
    
    if (!$this->userCanAccess($resource)) {
        throw new ForbiddenException(
            "You don't have permission to update this resource",
            403,
            null,
            ['resource_id' => $id, 'resource_type' => 'example']
        );
    }
    
    // Update logic here
}
```

### Validation Errors

```php
use App\Exceptions\CustomValidationException;

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
    ]);
    
    if ($validator->fails()) {
        throw new CustomValidationException(
            'Validation failed',
            $validator->errors()->toArray()
        );
    }
    
    // Create logic here
}
```

### Try-Catch Pattern

```php
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;

public function process(Request $request)
{
    try {
        // Some logic that might throw exceptions
        $result = $this->someService->process($request->all());
        return response()->json(['success' => true, 'data' => $result]);
        
    } catch (BadRequestException | NotFoundException $e) {
        // Handle specific exceptions
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'error_code' => $e->getErrorCode(),
        ], $e instanceof NotFoundException ? 404 : 400);
        
    } catch (\Exception $e) {
        // Handle any other exceptions
        report($e); // Log the exception
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred',
        ], 500);
    }
}
```

## Error Response Format

### API Responses

All API error responses follow this format:

```json
{
    "success": false,
    "message": "Error message describing what went wrong",
    "error_code": "ERROR_CODE",
    "errors": {
        "field1": ["Error message for field1"],
        "field2": ["Error message for field2"]
    }
}
```

- `success`: Always `false` for error responses
- `message`: Human-readable error message
- `error_code`: Machine-readable error code for easier debugging
- `errors`: Only present for validation errors, contains field-specific error messages

### Status Codes

The application uses standard HTTP status codes:

- `400` Bad Request
- `401` Unauthorized
- `403` Forbidden
- `404` Not Found
- `422` Unprocessable Entity (Validation Error)
- `500` Internal Server Error

## Error Pages

The application provides custom error pages for common HTTP errors:

- `403.blade.php` - Forbidden
- `404.blade.php` - Not Found
- `500.blade.php` - Server Error
- `generic.blade.php` - Fallback for other errors

These pages are located in `resources/views/errors/` and can be customized as needed.

## Testing Error Handling

The application provides an example controller (`ExampleExceptionController`) and routes for testing error handling:

### Web Routes

- `/example/exceptions` - Shows all available exception examples
- `/example/bad-request` - Triggers a 400 Bad Request error
- `/example/validation-error` - Triggers a 422 Validation Error
- `/example/unauthorized` - Triggers a 401 Unauthorized error
- `/example/forbidden` - Triggers a 403 Forbidden error
- `/example/not-found` - Triggers a 404 Not Found error
- `/example/server-error` - Triggers a 500 Server Error

### API Routes

- `/api/example/bad-request` - Triggers a 400 Bad Request error
- `/api/example/validation-error` - Triggers a 422 Validation Error
- `/api/example/unauthorized` - Triggers a 401 Unauthorized error
- `/api/example/forbidden` - Triggers a 403 Forbidden error
- `/api/example/not-found` - Triggers a 404 Not Found error
- `/api/example/server-error` - Triggers a 500 Server Error
- `/api/example/try-catch` - Demonstrates try-catch pattern with custom exceptions

These routes can be used to test how the application handles different types of errors.
