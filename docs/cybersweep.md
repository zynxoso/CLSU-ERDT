# CyberSweep Security Enhancement

## Overview

CyberSweep is a security enhancement feature implemented in the CLSU-ERDT Scholar Management System to protect against various security threats, particularly focusing on file uploads and data submissions. The feature provides an additional layer of security by scanning uploaded files and request data for suspicious patterns and potential security threats.

## Components

The CyberSweep feature consists of the following components:

1. **CyberSweepMiddleware**: A global middleware that scans all incoming requests for suspicious patterns and uploaded files for potential security threats.
2. **Enhanced File Validation**: Additional validation in the FundRequestController to verify file integrity and content type.
3. **Security Scanning Tracking**: Database fields to track whether documents have been security scanned and the results of those scans.

## Implementation Details

### CyberSweepMiddleware

The middleware performs the following security checks:

- **Request Data Scanning**: Checks all request data for suspicious patterns such as SQL injection attempts, XSS attacks, path traversal, and command injection.
- **File Upload Scanning**: For uploaded files, checks:
  - File size limits
  - Allowed file extensions and MIME types
  - File content for suspicious code or scripts

### Enhanced File Validation

The FundRequestController has been enhanced with additional validation for file uploads:

- Verification of file integrity
- Double-checking that file extensions match actual content types to prevent file type spoofing

### Security Scanning Tracking

The Document model has been extended with new fields to track security scanning:

- `security_scanned`: Boolean field indicating whether the document has been scanned
- `security_scanned_at`: Timestamp of when the document was scanned
- `security_scan_result`: Result of the security scan (e.g., "Passed", "Failed")

## How It Works

1. When a request is made to the application, the CyberSweepMiddleware intercepts it.
2. For non-GET requests, the middleware scans the request data for suspicious patterns.
3. If the request includes file uploads, the middleware scans the files for suspicious content.
4. The middleware logs any suspicious activity but allows the request to continue by default.
5. When a document is uploaded through the FundRequestController, additional validation is performed.
6. The document is marked as security scanned and the result is recorded in the database.

## Benefits

- **Enhanced Security**: Protects against common security threats like SQL injection, XSS, and malicious file uploads.
- **Audit Trail**: Provides a record of security scanning for compliance and auditing purposes.
- **Minimal Performance Impact**: Designed to have minimal impact on application performance while providing robust security.

## Future Enhancements

Potential future enhancements to the CyberSweep feature could include:

- Integration with external virus/malware scanning services
- Real-time blocking of suspicious requests rather than just logging
- More sophisticated content analysis for uploaded files
- User notifications for security issues
- Admin dashboard for monitoring security scan results

## Testing

The CyberSweep feature includes feature tests to verify its functionality:

- Tests for detecting suspicious file content
- Tests for allowing legitimate file content

Run the tests using:

```bash
php artisan test --filter=CyberSweepTest
```
