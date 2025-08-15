# Comprehensive Error Handling System Documentation

## Overview

This document describes the comprehensive error handling system implemented in the CLSU-ERDT Laravel application. The system provides robust error management across both client-side and server-side components, ensuring a smooth user experience and proper error logging.

## System Components

### 1. Error View Templates

Custom error pages for different HTTP status codes:

- **401.blade.php** - Authentication Required errors
- **403.blade.php** - Forbidden access errors (existing)
- **404.blade.php** - Not Found errors (existing)
- **422.blade.php** - Validation/Unprocessable Entity errors
- **429.blade.php** - Too Many Requests/Rate Limiting errors
- **500.blade.php** - Internal Server errors (existing)
- **503.blade.php** - Service Unavailable/Maintenance Mode errors
- **generic.blade.php** - Generic error template (existing)
- **redirect-loop.blade.php** - Redirect loop detection (existing)

**Features:**
- User-friendly error messages
- Conditional display of technical details in development
- Navigation options (back, home, sign in)
- Auto-refresh functionality for temporary errors
- Responsive design with consistent styling

### 2. Server-Side Error Handling

#### Exception Handler (app/Exceptions/Handler.php)

Enhanced Laravel's default exception handler with:

- **Comprehensive Exception Handling**: Specific handlers for validation, authentication, authorization, database, CSRF, rate limiting, and service unavailable errors
- **Environment-Aware Responses**: Different error details for production vs development
- **API vs Web Response Differentiation**: JSON responses for API requests, redirects for web requests
- **Detailed Logging**: Context-aware error logging with request details
- **Security**: Prevents logging of sensitive data

#### Custom Middleware

**ErrorHandlingMiddleware** (`app/Http/Middleware/ErrorHandlingMiddleware.php`):
- Global error catching and logging
- Request validation (size, suspicious patterns)
- Comprehensive error context logging
- Environment-aware error responses

**DatabaseErrorHandlingMiddleware** (`app/Http/Middleware/DatabaseErrorHandlingMiddleware.php`):
- Database connection health monitoring
- Specific handling for database errors (connection loss, constraint violations, etc.)
- Database error logging with context
- Graceful degradation for database issues

#### Error Handling Service

**LivewireErrorHandlingService** (`app/Services/LivewireErrorHandlingService.php`):
- Centralized error handling for Livewire components
- Component state management during errors
- Event emission for frontend handling
- User-friendly error message generation
- Environment-aware error details

#### Error Handling Trait

**HasErrorHandling** (`app/Traits/HasErrorHandling.php`):
- Reusable error handling capabilities for Livewire components
- Error state management (errors, success, loading)
- Action execution with automatic error handling
- Validation and authorization helpers
- Database operation error handling
- Retry mechanisms for failed operations

#### API Error Controller

**ErrorController** (`app/Http/Controllers/Api/ErrorController.php`):
- Centralized API error handling
- Client-side error logging endpoint
- System health check endpoint
- Error statistics and metrics
- Error simulation for testing (development only)

### 3. Client-Side Error Handling

**Note:** The JavaScript Error Handler has been removed from the system.

**Remaining Client-Side Features:**
- SweetAlert2 for user notifications
- Livewire built-in error handling
- Browser console error logging

**User Feedback:**
- SweetAlert2 integration for user-friendly messages
- Automatic retry mechanisms
- Request queuing during network issues
- Error reporting to server

**Features:**
- Network status monitoring
- Automatic retry for critical resources
- Error categorization and reporting
- User-friendly error messages
- Development vs production error details

### 4. Logging Configuration

Custom logging channels in `config/logging.php`:

- **client-errors**: Client-side JavaScript errors
- **error-handling**: General error handling logs
- **database-errors**: Database-specific errors

All channels use daily rotation with configurable retention periods.

### 5. Demo and Testing

#### Error Handling Demo

**Route**: `/error-demo`

**Components:**
- **ErrorHandlingDemo** Livewire component (`app/Livewire/ErrorHandlingDemo.php`)
- Demo page view (`resources/views/pages/error-demo.blade.php`)

**Testing Scenarios:**
- Validation errors
- Database errors
- Authorization errors
- General exceptions
- Network errors
- Query exceptions

#### API Testing Endpoints

Available at `/api/errors/test/*`:
- `/validation` - Test validation errors
- `/authentication` - Test authentication errors
- `/authorization` - Test authorization errors
- `/not-found` - Test 404 errors
- `/server` - Test server errors
- `/database` - Test database errors
- `/rate-limit` - Test rate limiting
- `/maintenance` - Test maintenance mode

**Utility Endpoints:**
- `/api/errors/health` - System health check
- `/api/errors/stats` - Error statistics
- `/api/errors/log` - Client error logging

## Implementation Details

### Error Flow

1. **Error Occurs**: Exception thrown in application
2. **Middleware Processing**: Error caught by custom middleware
3. **Exception Handler**: Processed by enhanced Handler
4. **Response Generation**: Appropriate response generated (JSON/redirect)
5. **Logging**: Error logged with context
6. **User Feedback**: User sees friendly error message
7. **Client Handling**: JavaScript handles client-side aspects

### Error Categories

**Validation Errors (422)**:
- Form validation failures
- Request validation errors
- Custom validation rules

**Authentication Errors (401)**:
- Unauthenticated access attempts
- Token expiration
- Invalid credentials

**Authorization Errors (403)**:
- Insufficient permissions
- Role-based access violations
- Resource access restrictions

**Database Errors**:
- Connection failures
- Query exceptions
- Constraint violations
- Transaction failures

**Network Errors**:
- API communication failures
- External service unavailability
- Timeout errors

**System Errors (500)**:
- Application exceptions
- Configuration errors
- Unexpected failures

### Security Considerations

- **Sensitive Data Protection**: Prevents logging of passwords, tokens, and API keys
- **Environment Awareness**: Limited error details in production
- **CSRF Protection**: Proper handling of token mismatches
- **Rate Limiting**: Protection against abuse
- **Input Validation**: Request size and pattern validation

### Performance Optimizations

- **Efficient Logging**: Structured logging with appropriate levels
- **Caching**: Error message caching where appropriate
- **Lazy Loading**: Components loaded on demand
- **Minimal Overhead**: Lightweight error handling

## Usage Examples

### Using the Error Handling Trait in Livewire Components

```php
use App\Traits\HasErrorHandling;

class MyComponent extends Component
{
    use HasErrorHandling;
    
    public function saveData()
    {
        $this->executeWithErrorHandling(function () {
            // Your logic here
            $this->validate();
            // Database operations
            $this->setSuccessMessage('Data saved successfully!');
        });
    }
}
```

### Client-Side Error Handling

```javascript
// The error handler is automatically loaded
// Listen for custom events
document.addEventListener('livewire:init', () => {
    Livewire.on('error-occurred', (event) => {
        // Handle error event
    });
});
```

### API Error Handling

```javascript
fetch('/api/data')
    .then(response => {
        if (!response.ok) {
            throw new Error('API Error');
        }
        return response.json();
    })
    .catch(error => {
        // Error automatically handled by global handler
    });
```

## Configuration

### Environment Variables

Relevant environment variables for error handling:

```env
APP_ENV=production          # Controls error detail visibility
APP_DEBUG=false            # Controls debug information
LOG_LEVEL=error            # Minimum log level
LOG_DAILY_DAYS=14          # Log retention period
```

### Middleware Registration

Middleware is registered in `app/Http/Kernel.php`:

```php
protected $middleware = [
    // ...
    \App\Http\Middleware\ErrorHandlingMiddleware::class,
    \App\Http\Middleware\DatabaseErrorHandlingMiddleware::class,
];
```

## Monitoring and Maintenance

### Log Files

Error logs are stored in `storage/logs/`:
- `client-errors-YYYY-MM-DD.log`
- `error-handling-YYYY-MM-DD.log`
- `database-errors-YYYY-MM-DD.log`
- `laravel-YYYY-MM-DD.log` (general application logs)

### Health Monitoring

Use the health check endpoint to monitor system status:
```
GET /api/errors/health
```

Response includes:
- Database connectivity
- Cache status
- Session functionality
- Overall system health

### Error Statistics

Monitor error trends with the statistics endpoint:
```
GET /api/errors/stats
```

## Best Practices

1. **Always Use the Trait**: Use `HasErrorHandling` trait in Livewire components
2. **Proper Exception Types**: Throw appropriate exception types
3. **User-Friendly Messages**: Provide clear, actionable error messages
4. **Log Context**: Include relevant context in error logs
5. **Test Error Scenarios**: Regularly test error handling paths
6. **Monitor Logs**: Regularly review error logs for patterns
7. **Update Error Messages**: Keep error messages current and helpful

## Troubleshooting

### Common Issues

**Error Views Not Displaying**:
- Check view file permissions
- Verify view cache is cleared
- Ensure proper route configuration

**JavaScript Errors Not Caught**:
- Check browser console for script errors
- Ensure SweetAlert2 is available
- Verify Livewire error handling is working

**Database Errors Not Handled**:
- Verify middleware is registered
- Check database connection
- Review database error logs

**API Errors Not Logged**:
- Verify API routes are configured
- Check CSRF token for POST requests
- Review API error controller

### Debug Mode

For development, enable debug mode to see detailed error information:

```env
APP_DEBUG=true
```

**Note**: Never enable debug mode in production as it exposes sensitive information.

## Conclusion

This comprehensive error handling system provides robust error management for the CLSU-ERDT application, ensuring users receive helpful feedback while maintaining system security and providing developers with detailed error information for debugging and monitoring.

The system is designed to be maintainable, extensible, and user-friendly, following Laravel best practices and modern web development standards.