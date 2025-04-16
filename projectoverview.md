# CLSU-ERDT Scholar Management System - Project Overview

## Project Description
Develop a secure, scalable web application using Laravel that manages scholar documents and fund disbursements for CLSU-ERDT with:
- Robust authentication
- Workflow management 
- Reporting capabilities

## Technical Requirements

### Core Architecture
- **Backend**: Laravel 10.x with MVC architecture
- **Database**: MySQL 8.x relational database
- **Caching**: Redis for caching and queue management
- **Frontend**: React.js (using Inertia.js or separate SPA)
- **API**: RESTful API for future mobile integration

### Authentication System
**Laravel Fortify Implementation**:
- Multi-role authentication (admin/scholar)
- Email verification flow
- Password reset with rate limiting
- Secure session management
- Role-based middleware (`AdminMiddleware`, `ScholarMiddleware`)
- Activity logging for all auth events

### Scholar Management Module
**Database Schema**:
- `users` table (base authentication)
- `scholar_profiles` table (one-to-one with users)
- Status enum: `['New', 'Ongoing', 'On Extension', 'Graduated', 'Terminated']`

**Features**:
- Admin CRUD interface with validation
- Scholar self-service profile management
- Document upload with Spatie Media Library
- Academic progress tracking

### Fund Request Workflow
**Database Schema**:
- `fund_requests` table (polymorphic relations)
- `request_types` lookup table
- `disbursements` table

**Implementation**:
- Multi-step form with draft saving
- Document upload validation (type/size)
- State machine for request lifecycle
- Notification system at each transition
- Budget utilization calculations

### Manuscript System
- Version control using git-like approach
- PDF preview with PDF.js integration
- Review workflow with comment threads
- Change tracking between versions

### Security Requirements
- Laravel Sanctum for API auth
- Configure CSP headers
- Automatic SQL injection prevention
- File upload validation (mime/extension)
- Daily database backups to cloud storage
- Activity logging for all sensitive operations

### Reporting Module
- Laravel Excel for export functionality
- Analytics dashboards with:
  - Scholar status distribution
  - Fund request metrics by type/time
  - Budget utilization visualizations
- Scheduled report generation

### UI/UX Requirements
- AdminLTE 3 theme with dark mode
- Responsive design (mobile-first approach)
- Accessible components (WCAG 2.1 AA)
- Real-time updates with Laravel Echo
- Interactive data tables with Livewire

### DevOps Setup
- Docker development environment
- CI/CD pipeline with GitHub Actions
- Automated testing (Feature/Unit)
- Horizon for queue monitoring
- Telescope for debugging

## Implementation Guidelines

### Development Approach
- Repository pattern for data access
- Service classes for business logic
- Form request validation
- Policy classes for authorization
- Event-driven architecture for workflows

### Key Packages
- Spatie Laravel Permissions
- Laravel Excel
- Laravel Horizon
- Laravel Telescope
- PHPStan for static analysis

### Database Optimization
- Proper indexing strategy
- Query caching
- Eager loading relationships
- Database transactions for financial ops

### Testing Requirements
- 80%+ test coverage
- Feature tests for all workflows
- Browser tests for critical paths
- Performance benchmarking

## Deliverables Timeline

### Phase 1 (4 weeks)
- Core authentication system
- Scholar management CRUD
- Basic fund request submission

### Phase 2 (6 weeks)
- Complete workflow engine
- Disbursement processing
- Manuscript system
- Basic reporting

### Phase 3 (2 weeks)
- Advanced analytics
- System hardening
- Documentation
- Staff training

## Acceptance Criteria
- All functional requirements implemented
- Passes OWASP security audit
- 3-second response time under load
- Comprehensive documentation
- Training materials for admins

## Special Considerations
- Audit trail for all financial transactions
- Admin dashboard with key metrics
- Data export functionality for compliance
- Design for future API expansion

## Theme Implementation

### Objective
Integrate a user-toggleable dark/light theme system that:
- Matches the modern aesthetic of Fowers&Saints dashboard
- Maintains ERDT branding consistency

### System Requirements

#### Toggle Mechanism
- **UI Element**: Sun/moon icon switcher in navigation header
- **Storage**: 
  - User preference stored in localStorage
  - Defaults to OS preference via `prefers-color-scheme`

#### Design Consistency
- **Layout**: 
  - Card-based design (mirroring Fowers&Saints)
  - Soft shadows and rounded corners
- **Color Scheme**:
  - Primary: `#3498db` (ERDT blue)
  - Secondary: `#2ecc71` (ERDT green)
  - Maintained in both light/dark variants

#### Themed Components
1. **Dashboard Elements**:
   - Analytics cards
   - Recent transactions
   - Quick action buttons

2. **Data Displays**:
   - Fund requests tables
   - Manuscript listings

3. **Interactive Elements**:
   - Form inputs
   - Buttons and controls
   - Modal dialogs