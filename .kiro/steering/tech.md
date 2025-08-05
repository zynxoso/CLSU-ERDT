# Technology Stack & Build System

## Core Technologies

### Backend
- **Laravel 12.x** - PHP framework with MVC architecture
- **PHP 8.2+** - Server-side language
- **MySQL 8.0+** - Primary database
- **Redis 6.0+** - Caching and session storage

### Frontend
- **Blade Templates** - Laravel's templating engine
- **Livewire 3.6+** - Dynamic components without JavaScript
- **Tailwind CSS 3.2+** - Utility-first CSS framework
- **Vite 6.x** - Modern build tool with hot reloading
- **SweetAlert2** - Enhanced alert dialogs
- **Bootstrap 5.3+** - Additional UI components

### Key Libraries & Packages
- **Laravel Fortify** - Authentication scaffolding
- **Inertia.js** - Modern monolith approach
- **JWT Auth** - API authentication
- **DomPDF** - PDF generation
- **PhpSpreadsheet** - Excel export functionality
- **Ziggy** - Laravel routes in JavaScript

## Build System

### Development Commands
```bash
# Start development environment (all services)
composer run dev

# Frontend development server
npm run dev

# Build production assets
npm run build

# Clean build artifacts
npm run clean

# Fresh install and build
npm run fresh

# Laravel development server
php artisan serve
```

### Asset Management
- **Vite configuration** handles CSS/JS bundling
- **Hot Module Replacement** for development
- **Asset versioning** for cache busting
- **CSS/JS minification** for production

### Testing
```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run parallel tests (faster)
php artisan test --parallel
```

### Code Quality
```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Real-time log monitoring
php artisan pail
```

## Environment Setup

### Requirements
- PHP 8.2+ with extensions (mbstring, xml, curl, zip, gd, pdo, redis)
- Node.js 18+ LTS
- Composer 2.x
- MySQL 8.0+ or PostgreSQL 13+
- Redis 6.0+

### Quick Setup Commands
```bash
# Install dependencies
composer install && npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed
php artisan storage:link

# Build assets
npm run build
```

## Production Optimization
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Windows-Specific Tools
- **quick-setup.bat** - Automated setup script
- **build-assets.bat** - Production asset builder
- **fix-vite.bat** - Vite troubleshooting utility