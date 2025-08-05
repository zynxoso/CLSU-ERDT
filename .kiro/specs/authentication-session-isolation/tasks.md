# Implementation Plan

- [ ] 1. Configure authentication guards and providers
  - Update `config/auth.php` to add separate admin and scholar guards
  - Configure providers for role-specific authentication
  - Test guard configuration with basic authentication
  - _Requirements: 3.1, 3.2_

- [ ] 2. Create session cleanup service
  - [ ] 2.1 Implement SessionCleanupService class
    - Create service class with comprehensive session cleanup methods
    - Add methods to clear all guard sessions and regenerate tokens
    - Include remember token cleanup functionality
    - _Requirements: 1.1, 1.2, 4.1, 4.2_

  - [ ] 2.2 Add session validation helpers
    - Create methods to validate session integrity
    - Add guard-specific session verification
    - Implement session corruption detection
    - _Requirements: 1.3, 2.3_

- [ ] 3. Update authentication controllers
  - [ ] 3.1 Modify AdminAuthenticatedSessionController
    - Update store method to use admin guard
    - Add comprehensive role validation before authentication
    - Implement enhanced logout with SessionCleanupService
    - Add proper error handling and logging
    - _Requirements: 1.1, 2.1, 3.2, 4.3_

  - [ ] 3.2 Modify ScholarAuthenticatedSessionController
    - Update store method to use scholar guard
    - Add comprehensive role validation before authentication
    - Implement enhanced logout with SessionCleanupService
    - Add proper error handling and logging
    - _Requirements: 1.1, 2.2, 3.1, 4.3_

- [ ] 4. Create ScholarMiddleware
  - Implement middleware to check scholar guard authentication
  - Add role validation for scholar access
  - Implement proper redirect to scholar login on failure
  - Add comprehensive logging for security monitoring
  - _Requirements: 1.4, 2.2, 3.1_

- [ ] 5. Update AdminMiddleware
  - Modify to use admin guard instead of web guard
  - Enhance role validation logic
  - Update redirect logic to use admin login
  - Improve error handling and logging
  - _Requirements: 1.4, 2.1, 3.2_

- [ ] 6. Update route definitions
  - [ ] 6.1 Update admin routes to use admin guard
    - Modify admin route group to use auth:admin middleware
    - Update AdminMiddleware application
    - Test admin route protection
    - _Requirements: 1.4, 2.1_

  - [ ] 6.2 Update scholar routes to use scholar guard
    - Modify scholar route group to use auth:scholar middleware
    - Apply ScholarMiddleware to scholar routes
    - Test scholar route protection
    - _Requirements: 1.4, 2.2_

- [ ] 7. Implement authentication state verification
  - Add methods to verify authentication consistency
  - Create helpers to detect cross-role authentication attempts
  - Implement automatic cleanup for invalid authentication states
  - Add logging for security violations
  - _Requirements: 1.3, 2.3, 2.4_

- [ ] 8. Create comprehensive test suite
  - [ ] 8.1 Write unit tests for SessionCleanupService
    - Test session cleanup methods
    - Test guard-specific session handling
    - Test token regeneration functionality
    - _Requirements: 1.1, 1.2, 4.1, 4.2_

  - [ ] 8.2 Write integration tests for authentication flows
    - Test admin login/logout flow with proper session cleanup
    - Test scholar login/logout flow with proper session cleanup
    - Test cross-role access prevention
    - _Requirements: 1.1, 1.2, 2.1, 2.2_

  - [ ] 8.3 Write security tests for session isolation
    - Test scholar logout followed by admin login attempt
    - Test admin logout followed by scholar login attempt
    - Test session persistence prevention across roles
    - _Requirements: 2.1, 2.2, 2.3, 2.4_

- [ ] 9. Add User model enhancements
  - Add guard-specific authentication helper methods
  - Implement role-based guard selection methods
  - Add session cleanup helper methods
  - Create methods to validate user authentication state
  - _Requirements: 3.1, 3.2, 3.3_

- [ ] 10. Update login request validation
  - Enhance LoginRequest to support guard-specific validation
  - Add role-based authentication logic
  - Implement proper error messages for authentication failures
  - Add security logging for failed authentication attempts
  - _Requirements: 1.3, 1.4, 2.1, 2.2_