# Authentication Story Timeline

The authentication system in CLSU-ERDT follows this chronological journey:

1. **Initial Setup (Development Phase)**
   - Laravel's authentication scaffolding is installed using Laravel Fortify
   - User roles ('admin' and 'scholar') are defined in the database migration
   - Custom middleware for role-based protection is created
   - Routes are organized into role-specific groups with appropriate protection
   - The system is configured to use MySQL for data persistence

2. **User Creation (Admin-Only)**
   - Only administrators can create new user accounts
   - Admin creates scholar accounts through the admin interface
   - Scholars cannot self-register; this is a deliberate security decision
   - The system uses Laravel's built-in password hashing via the `Hash` facade
   - Email verification is enabled but optional

3. **Login Process (User Authentication)**
   - User navigates to the login page
   - Laravel's authentication system processes login requests
   - The system uses traditional cookie-based authentication with session storage
   - Credentials are validated against the database records
   - Failed login attempts are rate-limited to prevent brute force attacks
   - Successful login regenerates the session for security

4. **Session Management (During Active Use)**
   - Laravel manages the user session with secure cookie settings
   - CSRF protection is enforced on all forms
   - The system uses Laravel's middleware system to protect routes
   - Role-specific functionality is protected by custom middleware
   - Laravel's authentication guards maintain the authentication state

5. **Access Control (During Application Use)**
   - Every request passes through appropriate middleware
   - Access is determined by the user's role stored in the database
   - Admin routes are protected by the `AdminMiddleware`
   - Scholar routes check for the appropriate user role
   - The system leverages Laravel's policy-based authorization for fine-grained control

6. **Audit Logging (Throughout User Journey)**
   - All significant authentication events are logged (login, logout, password changes)
   - The custom `AuditService` tracks user actions with timestamps and IP addresses
   - This creates a chronological record of all authentication activities
   - Logs are stored in the database for easy searching and reporting

7. **Security Measures (Continuous Protection)**
   - Passwords are hashed using bcrypt (Laravel's default)
   - Sessions are secured against fixation attacks
   - Input validation prevents malicious data entry
   - SQL injection is prevented through Laravel's query builder
   - XSS protection is applied to all output

8. **Password Management (As Needed)**
   - Users can request password resets via email
   - The system uses Laravel's built-in password reset functionality
   - Tokens are time-limited for security
   - Password strength requirements enforce secure passwords

9. **Logout Process (End of Session)**
   - User sessions are invalidated on logout
   - Session tokens are regenerated
   - The user is redirected to the login page
   - Audit logs record the logout event

This authentication journey leverages Laravel's robust security features while extending them with custom middleware and role-based protections specific to the CLSU-ERDT Scholar Management System. 
