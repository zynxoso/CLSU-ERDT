# CLSU-ERDT System Architecture Documentation

## System Overview
The CLSU-ERDT Document and Disbursement Management System is a web-based application built with PHP, MySQL, and traditional MVC-like architecture. This documentation outlines the current system structure and provides guidance for migration to Laravel/React stack.

## Current Technology Stack

### Backend Infrastructure
- **Core Language**: PHP (Traditional)
- **Database**: MySQL with PDO
- **Server**: Apache (XAMPP)
- **Authentication**: Session-based with CSRF protection
- **File Storage**: Local file system

### Frontend Components
- **Core**: HTML5, CSS3, JavaScript
- **Styling**: Custom CSS with modern design principles
- **Client-side Logic**: Vanilla JavaScript with some jQuery
- **Asset Management**: Static file serving

## System Architecture

### Authentication Flow
```
[Login Request] → [Input Validation] → [Credentials Check] → [Session Creation] → [Role-based Redirect]
```

### Request Processing Pipeline
1. **Entry Point**
   - URL routing through PHP files
   - Session validation
   - CSRF protection

2. **Authentication Layer**
   - Role verification
   - Permission checks
   - Session management

3. **Business Logic Layer**
   - Input sanitization
   - Data validation
   - Business rules enforcement

4. **Data Access Layer**
   - PDO database operations
   - Prepared statements
   - Transaction management

### Database Schema Overview

#### Core Tables
- `users`
  - User authentication
  - Role management
  - Profile information

- `scholars`
  - Scholar details
  - Academic information
  - Status tracking

- `fund_requests`
  - Request details
  - Status workflow
  - Financial tracking

- `manuscripts`
  - Document metadata
  - Review status
  - Version control

- `disbursements`
  - Payment records
  - Transaction tracking
  - Budget management

## Key Features and Workflows

### Scholar Management
```
Registration → Profile Creation → Document Submission → Status Updates → Graduation/Completion
```

### Fund Request Process
```
Request Creation → Document Upload → Admin Review → Approval/Rejection → Disbursement
```

### Manuscript Handling
```
Submission → Initial Review → Feedback → Revision → Final Approval
```

## Security Implementation

### Current Security Measures
- Password hashing
- CSRF protection
- Session security
- Input sanitization
- File upload validation
- PDO prepared statements

### Areas for Enhancement
- Environment variables for sensitive data
- Rate limiting implementation
- Password policy enforcement
- IP-based security measures

## Migration Considerations

### Laravel Migration Strategy

1. **Authentication System**
   - Migrate to Laravel Sanctum/Passport
   - Implement JWT for API authentication
   - Use Laravel's built-in security features

2. **Database Layer**
   - Convert schema to Laravel migrations
   - Implement Eloquent models
   - Define relationships
   - Set up factories and seeders

3. **Business Logic**
   - Create service classes
   - Implement repository pattern
   - Use Laravel's validation system
   - Implement event/listener pattern

4. **File Management**
   - Use Laravel's storage system
   - Implement file upload queue
   - Configure cloud storage integration

### React Frontend Implementation

1. **Component Structure**
   - Authentication views
   - Dashboard components
   - Form components
   - Table/list views
   - File upload components

2. **State Management**
   - Redux/Context setup
   - API integration
   - Form handling
   - Real-time updates

3. **UI/UX Considerations**
   - Responsive design
   - Modern UI components
   - Loading states
   - Error handling
   - Toast notifications

## API Structure (For Laravel/React)

### Authentication Endpoints
```
POST   /api/auth/login
POST   /api/auth/logout
POST   /api/auth/register
POST   /api/auth/password/reset
```

### Scholar Management
```
GET    /api/scholars
POST   /api/scholars
GET    /api/scholars/{id}
PUT    /api/scholars/{id}
DELETE /api/scholars/{id}
```

### Fund Requests
```
GET    /api/fund-requests
POST   /api/fund-requests
GET    /api/fund-requests/{id}
PUT    /api/fund-requests/{id}
PATCH  /api/fund-requests/{id}/status
```

### Manuscripts
```
GET    /api/manuscripts
POST   /api/manuscripts
GET    /api/manuscripts/{id}
PUT    /api/manuscripts/{id}
POST   /api/manuscripts/{id}/review
```

### Disbursements
```
GET    /api/disbursements
POST   /api/disbursements
GET    /api/disbursements/{id}
PUT    /api/disbursements/{id}
```

## Performance Optimization

### Current Bottlenecks
- Large dataset handling
- File upload processing
- Complex queries
- Session management

### Improvement Strategies
1. **Database Optimization**
   - Implement caching
   - Optimize indexes
   - Query optimization
   - Database connection pooling

2. **Asset Management**
   - CDN integration
   - Asset bundling
   - Image optimization
   - Lazy loading

3. **API Performance**
   - Response caching
   - Rate limiting
   - Pagination
   - Resource optimization

## Testing Strategy

### Backend Testing (Laravel)
- Unit tests for services
- Feature tests for API endpoints
- Database testing
- Authentication testing

### Frontend Testing (React)
- Component testing
- Integration testing
- E2E testing
- State management testing

## Deployment Considerations

### Environment Setup
- Development environment
- Staging environment
- Production environment
- CI/CD pipeline

### Server Requirements
- PHP 8.x
- Node.js
- MySQL 8.x
- Redis (optional)
- SSL certificate

## Maintenance and Monitoring

### Logging Strategy
- Error logging
- Activity logging
- Performance monitoring
- Security auditing

### Backup Strategy
- Database backups
- File backups
- Version control
- Disaster recovery plan

## User Authentication and Management

### Authentication Flow

The CLSU-ERDT system uses a secure authentication mechanism with the following features:

1. **Admin-Only User Creation**: All user accounts must be created by administrators. Self-registration is disabled.
2. **Role-Based Access**: Users are assigned either 'admin' or 'scholar' roles, determining available features.
3. **Secure Login**: Standard email/password authentication with rate limiting and lockout protection.
4. **Password Management**: Secure password storage (bcrypt hashing) and self-service reset functionality.
5. **Session Management**: Automated timeout for inactive sessions with renewal capabilities.

### User Management by Administrators

Administrators have exclusive control over user accounts:

1. **Account Creation**: Admins can create both scholar and admin accounts
2. **Role Assignment**: Initial role designation during account creation
3. **Account Maintenance**: Update user information, reset passwords, and disable/enable accounts
4. **Audit Trail**: All user management actions are logged for accountability
