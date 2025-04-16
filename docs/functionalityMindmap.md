# CLSU-ERDT System Functionality Mindmap

This document provides a comprehensive overview of all functionalities in the CLSU-ERDT Document and Disbursement Management System, serving as a guide for developers, administrators, and stakeholders.

## 1. Authentication System

### 1.1 User Authentication
- **Login Process**
  - Email and password validation
  - Role-based redirection (admin/scholar)
  - Session initialization with secure settings

### 1.2 Password Management
- **Password Reset Flow**
  - Forgot password request
  - Token generation and email delivery
  - Token validation
  - Password reset form
  - Password complexity validation

### 1.3 Session Management
- **Session Security**
  - Secure cookie settings
  - Session fixation protection
  - CSRF token validation
  - Session timeout handling
  - IP-based validation

### 1.4 Registration
- **Scholar Registration**
  - Account creation with email verification
  - Profile information collection
  - Document upload for verification
  - Admin approval workflow

## 2. Scholar Management

### 2.1 Scholar Profiles
- **Profile Data**
  - Personal information (name, contact, address)
  - Academic information
  - Status tracking (New, Ongoing, On Extension, Graduated, Terminated, Deferred Repayment)
  - Document storage

### 2.2 Scholar Administration (only foe ABE department)
- **Admin Operations**
  - Add new scholars
  - Edit scholar information
  - View scholar details
  - Update scholar status
  - Deactivate/reactivate accounts

### 2.3 Scholar Portal
- **Self-Service Features**
  - Profile management
  - Document submission
  - Fund request creation
  - Notification management
  - Status tracking

## 3. Fund Request System

### 3.1 Request Types
- **Supported Request Categories**
  - Tuition Fee
  - Stipend
  - Learning Materials and Connectivity Allowance
  - Transportation Allowance
  - Thesis/Dissertation Outright Grant
  - Research Support Grant - Equipment
  - Research Dissemination Grant
  - Mentor's Fee

### 3.2 Request Workflow
- **Scholar Actions**
  - Create new request
  - Upload supporting documents
  - Track request status
  - Respond to review comments

- **Admin Actions**
  - Review pending requests
  - Approve/reject requests
  - Request additional information
  - Process disbursements

### 3.3 Document Management
- **Document Handling**
  - File upload validation (size, type)
  - Document categorization
  - Version tracking
  - Document verification

## 4. Disbursement Management

### 4.1 Disbursement Process
- **Workflow**
  - Approved request verification
  - Disbursement record creation
  - Reference number generation
  - Notification to scholar

### 4.2 Financial Tracking
- **Record Keeping**
  - Disbursement history
  - Amount tracking
  - Budget utilization monitoring
  - Financial reporting

### 4.3 Disbursement Administration
- **Admin Controls**
  - Process disbursements
  - Edit disbursement details
  - View disbursement history
  - Generate financial reports

## 5. Manuscript Submission System

### 5.1 Submission Process
- **Scholar Actions**
  - Upload manuscript
  - Provide title and abstract
  - Track submission status (approved outline, accepted, published)
  - Respond to reviewer comments

### 5.2 Review Workflow
- **Admin Actions**
  - Review submitted manuscripts
  - Provide feedback
  - Approve/reject manuscripts
  - Request revisions

### 5.3 Manuscript Management
- **Document Handling**
  - generate manuscript excel sheet that will show manuscript/thesis title and author
  - File upload validation (size, type)
  - Document categorization
  - Version tracking
  - Upload manuscript
  - File storage and organization
  - Version tracking
  - Status updates
  - Notification system

## 6. Notification System

### 6.1 Notification Types
- **System Notifications**
  - Status updates
  - Request approvals/rejections
  - Disbursement confirmations
  - Manuscript review feedback
  - System announcements

### 6.2 Notification Delivery
- **Delivery Methods**
  - In-app notifications
  - Email notifications (for critical updates)
  - Read/unread status tracking

## 7. Audit Logging

### 7.1 Activity Tracking
- **Logged Actions**
  - User authentication events
  - Fund request operations
  - Disbursement processing
  - Manuscript submissions and reviews
  - Profile modifications
  - Administrative actions

### 7.2 Log Management
- **Admin Features**
  - View audit logs
  - Filter logs by user, action, date
  - Export logs for reporting
  - Security incident investigation

## 8. Reporting System

### 8.1 Administrative Reports
- **Available Reports**
  - Scholar status summary
  - Fund request statistics
  - Disbursement summary
  - Budget utilization
  - Manuscript submission metrics

### 8.2 Report Generation
- **Features**
  - Customizable date ranges
  - Filtering options
  - Export to CSV/PDF
  - Visual charts and graphs

## 9. Security Implementation

### 9.1 Data Protection
- **Security Measures**
  - Password hashing
  - Input sanitization
  - Prepared statements for database queries
  - File upload validation
  - CSRF protection

### 9.2 Access Control
- **Authorization**
  - Role-based access control
  - Permission validation
  - Secure session management
  - IP-based security

## 10. System Architecture

### 10.1 Technical Components
- **Backend**
  - PHP (Native/No Framework)
  - Session-based authentication
  - File handling system

- **Database**
  - MySQL
  - Relational database design
  - Stored procedures
  - Transaction management

- **Frontend**
  - Bootstrap 5
  - Modern UI components
  - Responsive design
  - Dark mode support

### 10.2 Data Flow
- **Request Processing**
  1. Authentication Layer
  2. Input Validation
  3. Business Logic Processing
  4. Database Operations
  5. Response Generation
  6. Audit Logging

## 11. Implementation Guide

### 11.1 Development Standards
- **Coding Practices**
  - Follow MVC-like architecture
  - Use prepared statements for database queries
  - Implement proper error handling
  - Document code with comments
  - Follow consistent naming conventions

### 11.2 Security Best Practices
- **Implementation Guidelines**
  - Store credentials in environment variables
  - Implement rate limiting for authentication
  - Add password complexity requirements
  - Use secure session handling
  - Implement Content Security Policy

### 11.3 Performance Optimization
- **Recommended Approaches**
  - Implement caching for frequently accessed data
  - Optimize database queries with proper indexes
  - Use pagination for large datasets
  - Implement lazy loading for resources
  - Monitor database performance

## 12. Future Enhancements

### 12.1 Planned Features
- **Upcoming Functionality**
  - Real-time notifications
  - Progress tracking for manuscript submissions
  - Document preview functionality
  - Bulk upload capabilities

### 12.2 Technical Improvements
- **Architecture Enhancements**
  - Refactor to proper MVC architecture
  - Create service layer for business logic
  - Add comprehensive input validation layer
  - Implement proper error handling middleware
  - Add API documentation


## Database Relationships

### Core Entities
- **users**
  - Primary entity for authentication
  - Linked to scholar_profiles (one-to-one)
  - Referenced by audit_logs (one-to-many)
  - Referenced by notifications (one-to-many)

- **scholar_profiles**
  - Linked to users (one-to-one)
  - Referenced by fund_requests (one-to-many)
  - Referenced by manuscripts (one-to-many)
  - Referenced by documents (one-to-many)

- **fund_requests**
  - Linked to scholar_profiles (many-to-one)
  - Linked to request_types (many-to-one)
  - Referenced by documents (one-to-many)
  - Referenced by disbursements (one-to-many)

- **disbursements**
  - Linked to fund_requests (many-to-one)
  - Linked to users (admin) for tracking who processed

- **manuscripts**
  - Linked to scholar_profiles (many-to-one)
  - Tracked through status workflow

- **documents**
  - Linked to fund_requests (many-to-one, optional)
  - Linked to scholar_profiles (many-to-one)
  - Can be verified by admin users

---

## User Interface Guidelines

### Theme System Requirements Toggle Mechanism
- Location : Navigation header (top-right corner)
- Visual Elements :
  - Sun icon (☀️) for light mode
  - Moon icon (🌙) for dark mode
- Behavior :
  - Toggles between light/dark modes on click
  - Shows current mode with icon
- Storage :
  - Uses localStorage to remember preference
  - Defaults to OS preference ( prefers-color-scheme )
  - Key: erdtThemePreference Design Consistency
- Layout Inspiration : Fowers&Saints card-based design
- Card Styling :
  - Rounded corners (0.5rem radius)
  - Subtle shadows (lighter for light mode, darker for dark mode)
  - Consistent padding (1.25rem)
- Color Scheme :
  - Primary: #3498db (blue)
  - Secondary: #2ecc71 (green)
  - Text: High contrast for readability
  - Background: Neutral tones Themed Components
- Dashboard Cards :
  - Analytics summary cards
  - Recent transaction cards
  - Quick action buttons
- Data Tables :
  - Fund requests listing
  - Manuscripts tracking table
  - Alternating row colors
- Forms & Controls :
  - Input fields with consistent styling
  - Buttons with primary/secondary states
  - Validation states (error/success) Implementation Notes
- CSS variables for easy theming
- Smooth transitions between modes
- Accessibility considerations:
  - Minimum contrast ratios
  - Focus states for keyboard na