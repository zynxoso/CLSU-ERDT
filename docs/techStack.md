# Technology Stack

This document outlines the technology stack used in the CLSU-ERDT (Central Luzon State University - Engineering Research and Development for Technology) system. The system is specifically designed for CLSU's Department of Agricultural and Biosystems Engineering (ABE) to manage scholars and fund requests.

## Backend Technologies

### Core Framework
- **PHP 8.2+**: Modern PHP version with improved performance and features
- **Laravel 12.x**: PHP web application framework providing elegant syntax and tools for common tasks
- **Laravel Fortify**: Complete authentication implementation including login, registration, password reset, and two-factor authentication
- **Laravel Sanctum**: For API token authentication and security
- **Laravel UI**: UI scaffolding for Laravel applications
- **JWT Auth**: JSON Web Token authentication for secure API access

### Database
- **MySQL**: Relational database management system
- **Laravel Migrations**: Database schema version control
- **Laravel Eloquent ORM**: Object-Relational Mapping for database interactions

### Caching
- **Redis**: In-memory data structure store used as a database, cache, and message broker
- **Predis**: PHP client library for Redis

### Additional Backend Components
- **Laravel Livewire 3.6+**: Full-stack framework for dynamic interfaces without writing JavaScript
- **Laravel Breeze**: Minimal authentication implementation
- **DomPDF**: PDF generation library for reports and documents
- **Laravel Tinker**: REPL for the Laravel framework

## Frontend Technologies

### Core Frontend
- **Blade Templating Engine**: Laravel's templating engine for views
- **Tailwind CSS 3.x**: Utility-first CSS framework for custom designs
- **Alpine.js**: Lightweight JavaScript framework for adding interactivity
- **Axios**: Promise-based HTTP client for making requests

### UI Components
- **Bootstrap 5.3**: Component library for responsive design (used alongside Tailwind)
- **SweetAlert2**: Library for beautiful, responsive, customizable alert dialogs

### Build Tools
- **Vite 6.x**: Next-generation frontend build tool
- **PostCSS**: Tool for transforming CSS with JavaScript plugins
- **Laravel Vite Plugin**: Integration between Laravel and Vite

## Development Tools

### Testing & Quality Assurance
- **PHPUnit**: Testing framework for PHP applications
- **Laravel Pint**: PHP code style fixer for Laravel
- **Laravel Sail**: Docker development environment for Laravel
- **Laravel Pail**: Real-time log viewer for Laravel applications

### Development Workflow
- **Composer**: PHP dependency manager
- **npm**: JavaScript package manager
- **Git**: Version control system

## Deployment & Environment

### Server Requirements
- **Apache/Nginx**: Web server
- **XAMPP**: Local development stack (Apache, MySQL, PHP, Pearl)

### Environment Management
- **.env**: Environment configuration file
- **Laravel Configuration System**: Application configuration management

## Architecture Patterns

- **MVC Pattern**: Model-View-Controller architecture
- **Repository Pattern**: For data access abstraction
- **Service Layer**: Business logic encapsulation
- **Livewire Components**: For reactive UI components

## Key Features

- **Scholar Management**: Tracking and managing CLSU ABE scholars
- **Fund Request System**: Processing and tracking fund requests
- **Reporting System**: Generation of reports and documents
- **User Authentication**: Role-based access control
- **Dashboard**: Data visualization and quick access to key functions
