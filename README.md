# CLSU-ERDT Scholar Management System

## Overview

The CLSU-ERDT (Central Luzon State University - Engineering Research and Development for Technology) Scholar Management System is a comprehensive web application built with Laravel that manages scholar documents, fund disbursements, and academic progress tracking. The system facilitates the administration of scholarship programs, tracking scholars' academic progress, managing fund requests, and handling manuscript submissions with a modern, responsive interface.

## System Architecture

### Technology Stack
- **Backend Framework**: Laravel 12.x with MVC architecture
- **Frontend**: Blade templates with Livewire 3.6+ for dynamic components
- **Database**: MySQL 8.0+ with comprehensive audit logging
- **Authentication**: Laravel Fortify with JWT support
- **Caching**: Redis with Predis client for session and cache management
- **CSS Framework**: Tailwind CSS 3.2+ with custom components
- **Build Tool**: Vite 6.x for modern asset bundling and hot reloading
- **Node.js**: Version 18+ (LTS recommended)
- **PDF Generation**: DomPDF for report generation
- **Excel Export**: PhpSpreadsheet for data exports

## Core Features

### 1. Multi-Role Authentication System

**Roles:**
- **Admins**: Full system management, scholar oversight, fund approval
- **Scholars**: Document submission, fund requests, manuscript management
- **Super Admins**: System configuration, user management, analytics

**Authentication Features:**
- Email-based authentication with secure password policies
- JWT token support for API authentication
- Role-based access control with granular permissions
- Session management with Redis backend
- Password reset functionality with secure tokens
- Activity logging for all authentication events

### 2. Scholar Profile Management

Comprehensive scholar profiles including:
- **Personal Information**: Name, contact details, address, student ID
- **Academic Details**: University, department, program, enrollment status
- **Scholarship Status**: New, Ongoing, On Extension, Graduated, Terminated
- **Document Portfolio**: Academic records, certificates, supporting documents
- **Performance Tracking**: Fund utilization, academic progress, milestone completion
- **Timeline Management**: Application progress, status changes, important dates

### 3. Fund Request System

**Request Types:**
- Configurable request types with specific requirements
- Amount limits and approval workflows
- Document attachment requirements

**Workflow:**
- Draft → Submitted → Under Review → Approved/Rejected → Disbursed
- Real-time status updates and notifications
- Admin review with comments and feedback
- Disbursement tracking and financial reporting
- Budget allocation monitoring

**Features:**
- Multi-document upload support
- File validation and security scanning
- Status history tracking
- Automated notifications
- Bulk operations for admins

### 4. Document Management System

**Document Types:**
- Personal identification documents
- Academic transcripts and certificates
- Fund request supporting documents
- Manuscript submissions
- Research outputs

**Features:**
- Secure file upload with virus scanning
- Document verification workflow
- Version control and history tracking
- Encrypted storage with access controls
- Batch download capabilities
- Document status tracking (Pending, Verified, Rejected)

### 5. Manuscript Management

**Academic Research Features:**
- Manuscript submission and tracking
- Version control for document revisions
- Peer review workflow with comments
- Status progression: Draft → Submitted → Under Review → Approved/Rejected
- Export capabilities for various formats
- Batch operations for administrators

### 6. Advanced Analytics & Reporting

**Dashboard Analytics:**
- Real-time scholar statistics
- Fund request metrics and trends
- Document verification status
- Performance indicators and KPIs

**Report Generation:**
- Scholar status distribution reports
- Fund utilization analysis
- Document verification reports
- Custom date range filtering
- Export to PDF, Excel, and CSV formats
- Scheduled report generation

### 7. Comprehensive Audit System

**Audit Logging:**
- All user actions tracked with timestamps
- CRUD operations on critical entities
- Fund approval/rejection events
- Document verification activities
- Profile changes with detailed diffs
- System security events

**Compliance Features:**
- Immutable audit trail
- User activity reports
- Data integrity verification
- Export capabilities for compliance reporting

## Database Schema

### Core Models

1. **User**: Authentication and basic user information
2. **ScholarProfile**: Extended scholar information and academic details
3. **FundRequest**: Fund request records with complete workflow tracking
4. **RequestType**: Configurable fund request types and requirements
5. **Document**: Document management with metadata and file references
6. **Disbursement**: Fund disbursement records and tracking
7. **Manuscript**: Academic manuscript submissions and reviews
8. **ReviewComment**: Comments and feedback on manuscripts
9. **AuditLog**: Comprehensive system activity logging
10. **CustomNotification**: User notification system
11. **Announcement**: System-wide announcements
12. **ApplicationTimeline**: Scholarship application process tracking
13. **ImportantNote**: Administrative notes and reminders

15. **HistoryAchievement**: Achievement tracking
16. **HistoryContentBlock**: Content management for history pages
17. **SiteSetting**: System configuration and settings
18. **FacultyMember**: Faculty and staff information

### Key Relationships
- One-to-one: User ↔ ScholarProfile
- One-to-many: ScholarProfile → FundRequest, Document, Manuscript
- One-to-many: FundRequest → Disbursement
- Polymorphic: Comments, Attachments, Audit Logs

## Application Workflow

### Scholar Journey
1. **Registration & Profile Setup**: Complete academic and personal information
2. **Document Upload**: Submit required verification documents
3. **Fund Request Submission**: Create requests with supporting documentation
4. **Status Tracking**: Monitor request progress with real-time updates
5. **Manuscript Management**: Submit and track research outputs
6. **Performance Dashboard**: Access analytics and progress reports

### Administrator Workflow
1. **Scholar Management**: Review and approve scholar profiles
2. **Fund Request Processing**: Evaluate, approve, or reject funding requests
3. **Document Verification**: Review and verify submitted documents
4. **Disbursement Management**: Process approved fund disbursements
5. **Manuscript Review**: Provide feedback on research submissions
6. **Analytics & Reporting**: Generate comprehensive system reports

## Security & Compliance

### Security Features
- **Input Validation**: Comprehensive form validation with custom rules
- **File Upload Security**: Virus scanning, type validation, size limits
- **CSRF Protection**: All forms protected with Laravel's CSRF tokens
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Output escaping and Content Security Policy
- **Rate Limiting**: API endpoints protected against abuse
- **Audit Logging**: Complete action tracking for compliance

### Data Protection
- **Encryption**: Sensitive data encrypted at rest and in transit
- **Access Control**: Role-based permissions with granular controls
- **Session Security**: Secure session handling with Redis
- **Password Security**: Bcrypt hashing with configurable complexity
- **File Storage**: Secure file storage with access controls

## Installation & Setup

### System Requirements

**Server Requirements:**
- PHP 8.2+ with required extensions (mbstring, xml, curl, zip, gd, pdo, redis)
- MySQL 8.0+ or PostgreSQL 13+
- Redis 6.0+ for caching and sessions
- Nginx (recommended) or Apache 2.4+
- SSL certificate for production

**Development Requirements:**
- Node.js 18+ LTS
- Composer 2.x
- NPM or Yarn
- Git

### Quick Setup

```bash
# Clone repository
git clone https://github.com/your-org/clsu-erdt.git
cd clsu-erdt

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed
php artisan storage:link

# Build assets
npm run build

# Start development server
php artisan serve
```

### Production Deployment

```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 755 storage bootstrap/cache
```

## Testing

### Test Structure
After recent cleanup, the test suite includes:

**Feature Tests:**
- `tests/Feature/Auth/` - Authentication system tests (5 files)
  - AuthenticationTest.php
  - PasswordConfirmationTest.php
  - PasswordResetTest.php
  - PasswordUpdateTest.php
  - RegistrationTest.php
- `tests/Feature/Scholar/` - Scholar functionality tests (6 files)
  - FundRequestTest.php (comprehensive fund request testing)
  - ScholarDashboardTest.php
  - ScholarDocumentsTest.php
  - ScholarManuscriptsTest.php
  - ScholarProfileTest.php
- `tests/Feature/` - General application tests
  - ProfileTest.php
  - CyberSweepTest.php (security testing)

**Base Test Class:**
- `tests/TestCase.php` - Base test configuration

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature

# Run parallel tests (faster)
php artisan test --parallel
```

## Development Tools

### Available Commands

```bash
# Development server with hot reloading
npm run dev

# Production build
npm run build

# Clean build artifacts
npm run clean

# Fresh install and build
npm run fresh

# Comprehensive development environment
composer run dev
```

### Code Quality Tools
- **Laravel Pint**: Code formatting and style checking
- **PHPUnit**: Unit and feature testing
- **Laravel Pail**: Real-time log monitoring
- **Vite**: Modern build tool with hot module replacement

## API Features

### Rate Limiting
- **Upload endpoints**: Protected against abuse
- **AJAX endpoints**: Rate limited for performance
- **Sensitive operations**: Extra protection for critical actions
- **Admin operations**: Separate limits for administrative tasks

### Security Middleware
- **AdminMiddleware**: Role-based access control
- **ApiRateLimitMiddleware**: Configurable rate limiting
- **CheckPasswordExpiration**: Password policy enforcement

## Performance Optimization

### Caching Strategy
- **Redis**: Session storage and application cache
- **Route Caching**: Optimized routing for production
- **View Caching**: Compiled Blade templates
- **Config Caching**: Cached configuration files

### Database Optimization
- **Eloquent Relationships**: Optimized queries with eager loading
- **Database Indexing**: Strategic indexes for performance
- **Query Optimization**: Efficient data retrieval patterns

## Monitoring & Maintenance

### Logging
- **Application Logs**: Comprehensive error and activity logging
- **Audit Logs**: Complete user action tracking
- **Security Logs**: Authentication and security events
- **Performance Logs**: Query and response time monitoring

### Regular Maintenance
```bash
# Clear application cache
php artisan optimize:clear

# Update dependencies
composer update && npm update

# Database maintenance
php artisan model:prune

# Log rotation
php artisan log:clear
```

## Default Credentials

**Admin Account:**
- Email: admin@clsu-erdt.edu.ph
- Password: password

**Test Scholar Account:**
- Email: scholar@example.com
- Password: password

> **Security Note**: Change all default passwords immediately in production environments.

## Contributing

### Development Workflow
1. Fork the repository
2. Create a feature branch
3. Write comprehensive tests
4. Implement the feature
5. Run tests and ensure they pass
6. Submit a pull request

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel conventions
- Write tests for new functionality
- Document all public methods
- Use meaningful commit messages

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support & Documentation

**Contact:**
- Email: support@clsu-erdt.edu.ph
- Documentation: Check the `docs/` directory
- Issues: Report via GitHub Issues

**Additional Resources:**
- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

---

**Last Updated**: January 2025
**Version**: 2.0.0
