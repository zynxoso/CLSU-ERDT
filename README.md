# CLSU-ERDT Scholar Management System

## Overview

The CLSU-ERDT (Central Luzon State University - Engineering Research and Development for Technology) Scholar Management System is a comprehensive web application built with Laravel that manages scholar documents and fund disbursements. The system facilitates the administration of scholarship programs, tracking scholars' academic progress, managing fund requests, and handling manuscript submissions.

## System Architecture

### Technology Stack
- **Backend Framework**: Laravel 11.x with MVC architecture
- **Frontend**: Blade templates with modern JavaScript
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Fortify with email verification
- **Caching**: Redis (recommended for production)
- **CSS Framework**: Tailwind CSS v3.4+ with JIT compilation
- **Build Tool**: Vite 5.x for modern asset bundling
- **Node.js**: Version 18+ (LTS recommended)

## Core Modules

### 1. Authentication & User Management

The system implements a multi-role authentication system with two primary roles:
- **Admins**: Manage scholars, approve fund requests, and oversee the entire system
- **Scholars**: Submit documents, make fund requests, and track their academic progress

Features:
- Email verification for new accounts
- Password reset functionality with secure tokens
- Session management with Redis (production)
- Role-based access control with policies
- Activity logging for all authentication events
- Two-factor authentication support (configurable)

### 2. Scholar Management

The system maintains comprehensive profiles for each scholar, containing:
- Personal information (name, contact details, address)
- Academic details (university, program, department)
- Scholarship status tracking with timeline
- Document management with verification workflow
- Fund request history with detailed tracking
- Performance metrics and reporting

Scholar statuses include: New, Ongoing, On Extension, Graduated, and Terminated.

### 3. Fund Request System

Scholars can submit requests for funding through a structured workflow:
- Multiple request types supported (configured in the database)
- Document attachment requirements with validation
- Status tracking (Draft, Submitted, Under Review, Approved, Rejected, Disbursed)
- Admin review and approval process with comments
- Disbursement tracking with financial reporting
- Budget allocation and monitoring

### 4. Document Management

The system manages various documents with secure handling:
- Personal identification documents
- Academic records and transcripts
- Fund request supporting documents
- Verification workflow with admin review
- Status tracking (Pending, Verified, Rejected)
- Secure file storage with encryption
- Version control for document updates

### 5. Manuscript System

For academic research management:
- Version control for manuscript submissions
- Peer review workflow
- Comment functionality with threading
- Status tracking from draft to publication
- Plagiarism detection integration (configurable)
- Export capabilities for various formats

### 6. Audit Logging

Comprehensive audit logging tracks all significant actions:
- User authentication events
- CRUD operations on key entities
- Fund approval/rejection events
- Document verification activities
- Profile changes with diff tracking
- System-wide security events

### 7. Reporting & Analytics

The system provides advanced reporting capabilities:
- Scholar status distribution with trends
- Fund request metrics and analytics
- Budget utilization reports
- Performance dashboards
- Exportable reports in multiple formats (PDF, Excel, CSV)
- Scheduled report generation

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
10. **notifications**: User notifications and preferences

### Key Relationships
- One-to-one relationship between users and scholar_profiles
- One-to-many relationship between scholar_profiles and fund_requests
- One-to-many relationship between fund_requests and documents
- One-to-many relationship between fund_requests and disbursements
- One-to-many relationship between scholar_profiles and manuscripts
- Polymorphic relationships for comments and attachments

## Application Flow

### Scholar Workflow
1. Scholar registers and completes profile verification
2. Scholar uploads required documents for verification
3. Scholar submits fund requests with supporting documents
4. Scholar tracks request status and receives real-time notifications
5. Scholar submits and manages manuscript versions
6. Scholar accesses performance dashboard and reports

### Admin Workflow
1. Admin reviews and verifies scholar profiles
2. Admin reviews and approves/rejects fund requests with comments
3. Admin processes disbursements and tracks budget utilization
4. Admin reviews and provides feedback on manuscripts
5. Admin generates comprehensive reports and analytics
6. Admin manages system configuration and user permissions

## Security Features

- **CSRF Protection**: All forms protected with CSRF tokens
- **Input Validation**: Comprehensive form validation with custom rules
- **File Upload Security**: Virus scanning and file type validation
- **Role-Based Access Control**: Granular permissions with policies
- **Audit Logging**: Complete action tracking for compliance
- **Password Security**: Bcrypt hashing with configurable rounds
- **Session Security**: Secure session handling with Redis
- **API Rate Limiting**: Protection against brute force attacks
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Output escaping and Content Security Policy

## Development Methodology

The project follows modern development practices:
- **Agile Methodology**: 2-week development sprints
- **Feature-Based Development**: Modular architecture
- **Test-Driven Development**: Comprehensive test coverage
- **Code Quality**: PHPStan, Laravel Pint, and ESLint integration
- **Documentation-First**: Comprehensive documentation practice
- **Continuous Integration**: Automated testing and deployment
- **Version Control**: Git flow with feature branches

## Implementation Patterns

- **Repository Pattern**: Clean data access layer
- **Service Layer**: Business logic separation
- **Event-Driven Architecture**: Decoupled notifications
- **Policy-Based Authorization**: Fine-grained access control
- **Domain-Driven Design**: Clear domain boundaries
- **SOLID Principles**: Maintainable and extensible code

## System Requirements

### Server Requirements
- **PHP**: 8.2 or higher with required extensions
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Nginx (recommended) or Apache 2.4+
- **Memory**: Minimum 512MB, 2GB+ recommended
- **Storage**: SSD recommended for performance

### Development Requirements
- **PHP**: 8.2+ with extensions (mbstring, xml, curl, zip, gd, pdo)
- **Composer**: Latest version
- **Node.js**: 18+ LTS version
- **NPM/Yarn**: Latest versions
- **Redis**: 6.0+ (optional for development, required for production)

## Installation Steps

### 1. Environment Setup

First, ensure you have the required versions:

```bash
# Check PHP version (8.2+)
php -v

# Check Node.js version (18+)
node -v

# Check NPM version
npm -v
```

### 2. Clone and Setup

```bash
# Clone the repository
git clone https://github.com/your-org/clsu-erdt.git
cd clsu-erdt

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Configure your database in the `.env` file:

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clsu_erdt
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Redis Configuration (recommended)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@clsu-erdt.edu.ph
MAIL_FROM_NAME="CLSU-ERDT System"
```

### 5. Database Setup

```bash
# Run migrations and seeders
php artisan migrate --seed

# Create storage link for file uploads
php artisan storage:link
```

### 6. Asset Compilation

```bash
# Build assets for development
npm run dev

# Or build for production
npm run build
```

### 7. Start Development Server

```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite dev server (for hot reloading)
npm run dev
```

The application will be available at `http://localhost:8000`.

### 8. Queue Worker (Optional)

For production or testing notifications:

```bash
# Start queue worker
php artisan queue:work
```

## Default Credentials

After seeding, you can log in with these default accounts:

**Admin:**
- Email: admin@clsu-erdt.edu.ph
- Password: password

**Scholar (test account):**
- Email: scholar@example.com
- Password: password

> **Note**: Change these credentials immediately in production environments.

## Production Deployment

### 1. Server Setup

```bash
# Install required software
sudo apt update
sudo apt install nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-mbstring redis-server

# Install Node.js 18+ LTS
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 2. Application Deployment

```bash
# Clone and setup
git clone https://github.com/your-org/clsu-erdt.git /var/www/clsu-erdt
cd /var/www/clsu-erdt

# Install dependencies
composer install --optimize-autoloader --no-dev
npm ci

# Set permissions
sudo chown -R www-data:www-data /var/www/clsu-erdt
sudo chmod -R 755 /var/www/clsu-erdt/storage
sudo chmod -R 755 /var/www/clsu-erdt/bootstrap/cache

# Build assets
npm run build

# Run optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Web Server Configuration

Example Nginx configuration:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/clsu-erdt/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Performance Optimization

### 1. Caching Strategy

```bash
# Enable all caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear caches when needed
php artisan optimize:clear
```

### 2. Database Optimization

- Use database indexing for frequently queried columns
- Implement query optimization with Eloquent relationships
- Use Redis for session and cache storage
- Consider database connection pooling for high traffic

### 3. Asset Optimization

```bash
# Production build with optimization
npm run build

# Enable Gzip compression in web server
# Use CDN for static assets
# Implement browser caching headers
```

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run parallel tests (faster)
php artisan test --parallel
```

### Test Structure

- **Unit Tests**: Individual class and method testing
- **Feature Tests**: HTTP endpoint and integration testing
- **Browser Tests**: Laravel Dusk for UI testing (optional)

## Maintenance

### Regular Tasks

```bash
# Update dependencies
composer update
npm update

# Clear application cache
php artisan optimize:clear

# Run database maintenance
php artisan model:prune

# Generate fresh API documentation
php artisan scribe:generate
```

### Monitoring

- Monitor application logs in `storage/logs/`
- Set up log rotation and monitoring
- Monitor database performance and queries
- Track application metrics and user activity

## Troubleshooting

### Common Issues

1. **Permission Issues**: Ensure proper file permissions for storage and cache directories
2. **Database Connection**: Verify database credentials and server connectivity
3. **Asset Issues**: Run `npm run build` after any frontend changes
4. **Cache Problems**: Clear all caches with `php artisan optimize:clear`
5. **Queue Issues**: Restart queue workers after code changes

### Debug Mode

For development debugging:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

> **Warning**: Never enable debug mode in production environments.

## Contributing

### Development Workflow

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Write tests for new functionality
4. Implement the feature
5. Run tests and ensure they pass
6. Submit a pull request

### Code Standards

- Follow PSR-12 coding standards
- Use Laravel coding conventions
- Write comprehensive tests
- Document all public methods
- Use meaningful commit messages

## Additional Resources

- **Laravel Documentation**: [https://laravel.com/docs](https://laravel.com/docs)
- **Tailwind CSS Documentation**: [https://tailwindcss.com/docs](https://tailwindcss.com/docs)
- **Vite Documentation**: [https://vitejs.dev/guide/](https://vitejs.dev/guide/)
- **Laravel Fortify**: [https://laravel.com/docs/fortify](https://laravel.com/docs/fortify)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For technical support or questions:

- **Email**: support@clsu-erdt.edu.ph
- **Documentation**: Check the `docs/` directory for detailed guides
- **Issues**: Report bugs via GitHub Issues

## Acknowledgments

- **Central Luzon State University** - Engineering Research for Development and Technology (CLSU-ERDT) program
- **Laravel Framework** and its community
- **Tailwind CSS** and **Vite** communities
- All contributors to this project

---

**Last Updated**: January 2025
