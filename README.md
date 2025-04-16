# CLSU-ERDT Scholar Management System

## Overview

The CLSU-ERDT (Central Luzon State University - Engineering Research and Development for Technology) Scholar Management System is a comprehensive web application built with Laravel that manages scholar documents and fund disbursements. The system facilitates the administration of scholarship programs, tracking scholars' academic progress, managing fund requests, and handling manuscript submissions.

## System Architecture

### Technology Stack
- **Backend Framework**: Laravel 10.x with MVC architecture
- **Frontend**: Blade templates with JavaScript
- **Database**: MySQL 8.x
- **Authentication**: Laravel Fortify with email verification
- **Caching**: Redis (configured)
- **CSS Framework**: Tailwind CSS (configured through Vite)

## Core Modules

### 1. Authentication & User Management

The system implements a multi-role authentication system with two primary roles:
- **Admins**: Manage scholars, approve fund requests, and oversee the entire system
- **Scholars**: Submit documents, make fund requests, and track their academic progress

Features:
- Email verification for new accounts
- Password reset functionality
- Session management
- Role-based access control
- Activity logging for all authentication events

### 2. Scholar Management

The system maintains comprehensive profiles for each scholar, containing:
- Personal information (name, contact details, address)
- Academic details (university, program, department)
- Scholarship status tracking
- Document management
- Fund request history

Scholar statuses include: New, Ongoing, On Extension, Graduated, and Terminated.

### 3. Fund Request System

Scholars can submit requests for funding through a structured workflow:
- Multiple request types supported (configured in the database)
- Document attachment requirements
- Status tracking (Draft, Submitted, Under Review, Approved, Rejected)
- Admin review and approval process
- Disbursement tracking

### 4. Document Management

The system manages various documents:
- Personal identification documents
- Academic records
- Fund request supporting documents
- Verification workflow for documents
- Status tracking (Pending, Verified, Rejected)

### 5. Manuscript System

For academic research management:
- Version control for manuscript submissions
- Review workflow
- Comment functionality
- Status tracking from draft to publication

### 6. Audit Logging

Comprehensive audit logging tracks all significant actions:
- User authentication events
- CRUD operations on key entities
- Fund approval/rejection events
- Document verification
- Profile changes

### 7. Reporting

The system provides various reporting capabilities:
- Scholar status distribution
- Fund request metrics
- Budget utilization
- Exportable reports in different formats

## Database Schema

### Primary Tables
1. **users**: Core authentication table (Laravel default)
2. **scholar_profiles**: Extended profile information for scholars
3. **fund_requests**: Fund request records with status tracking
4. **request_types**: Configuration for different types of fund requests
5. **documents**: Document records with metadata and file references
6. **disbursements**: Records of fund disbursements
7. **manuscripts**: Scholar manuscript submissions
8. **review_comments**: Comments on manuscript submissions
9. **audit_logs**: System-wide action logging
10. **notifications**: User notifications

### Key Relationships
- One-to-one relationship between users and scholar_profiles
- One-to-many relationship between scholar_profiles and fund_requests
- One-to-many relationship between fund_requests and documents
- One-to-many relationship between fund_requests and disbursements
- One-to-many relationship between scholar_profiles and manuscripts

## Application Flow

### Scholar Workflow
1. Scholar registers and completes profile
2. Scholar uploads required documents
3. Scholar submits fund requests with supporting documents
4. Scholar tracks request status and receives notifications on updates
5. Scholar submits and manages manuscript versions

### Admin Workflow
1. Admin reviews and verifies scholar profiles
2. Admin reviews and approves/rejects fund requests
3. Admin processes disbursements
4. Admin reviews and provides feedback on manuscripts
5. Admin generates reports and analytics

## Security Features

- CSRF protection
- Form validation
- Secure file upload handling
- Role-based access control
- Audit logging
- Password hashing
- Session security

## Development Methodology

The project follows an Agile methodology with:
- 2-week development sprints
- Feature-based organization
- Test-driven development approach
- Code quality tools integration
- Documentation-focused practice

## Implementation Patterns

- Repository pattern for data access
- Service classes for business logic
- Event-driven architecture for notifications
- Policy-based authorization
- Domain-driven design concepts

## Deployment

### Requirements
- PHP 8.0 or higher
- Composer
- MySQL 8.0 or higher
- Node.js and NPM (for frontend assets)
- Redis (optional, for caching and queues)

### Installation Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/your-org/clsu-erdt.git
   cd clsu-erdt
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install frontend dependencies:
   ```bash
   npm install
   npm run build
   ```

4. Copy the environment file and set your configuration:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=clsu_erdt
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```

7. Link storage for file uploads:
   ```bash
   php artisan storage:link
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

### Default Credentials

After seeding, you can log in with these default accounts:

**Admin:**
- Email: admin@clsu-erdt.edu.ph
- Password: password

**Scholar (test account):**
- Email: scholar@example.com
- Password: password

## Additional Resources

- Laravel Documentation: [https://laravel.com/docs](https://laravel.com/docs)
- Tailwind CSS: [https://tailwindcss.com/docs](https://tailwindcss.com/docs)

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Central Luzon State University Engineering Research for Development and Technology (CLSU-ERDT) program
- Laravel Framework and its community
- All contributors to this project

## Authentication System

The system utilizes Laravel's built-in authentication system with the following modifications:

- User registration is disabled for the public - accounts are created exclusively by administrators
- Role-based access control (admin vs scholar)
- Email verification for new accounts
- Password reset functionality
- Session management
