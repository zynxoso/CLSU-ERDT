# Software Requirements Specification (SRS)
CLSU-ERDT Document and Disbursement Management System

## 1. Introduction

### 1.1 Purpose
This document provides a detailed specification of requirements for the CLSU-ERDT Document and Disbursement Management System, focusing on scholar management, fund request processing, and manuscript handling.

### 1.2 Scope
The system encompasses scholar management, fund disbursement, document tracking, and reporting functionalities for CLSU-ERDT administrators and scholars.

### 1.3 Definitions and Acronyms
- CLSU: Central Luzon State University
- ERDT: Engineering Research and Development for Technology
- SRS: Software Requirements Specification

## 2. System Description

### 2.1 System Context
A web-based platform that facilitates the management of ERDT scholars, their fund requests, manuscript submissions, and related administrative processes.

### 2.2 System Features
1. Scholar Management
2. Fund Request Processing
3. Manuscript Submission and Review
4. Disbursement Tracking
5. Reporting and Analytics

## 3. Specific Requirements

### 3.1 User Classes and Characteristics

#### 3.1.1 Administrators
- Full access to system management
- Review and approval capabilities
- Report generation access
- User management rights

#### 3.1.2 Scholars
- Personal profile management
- Fund request submission
- Manuscript submission
- Status tracking

### 3.2 Functional Requirements

#### 3.2.1 Scholar Management
1. Scholar Registration
   - System shall allow scholars to register with valid credentials
   - Required fields: name, email, contact number, program, specialization
   - Email verification required

2. Profile Management
   - Scholars can update personal information
   - Upload profile picture
   - Update contact details
   - View academic progress

#### 3.2.2 Fund Request Processing
1. Request Submission
   - Submit fund requests with supporting documents
   - Select request type (tuition, research, allowance)
   - Attach required documentation
   - Save drafts before submission

2. Request Review
   - Admin review interface
   - Document verification checklist
   - Approval/rejection with comments
   - Status tracking

#### 3.2.3 Manuscript Management
1. Submission
   - Upload manuscript files
   - Version control
   - Progress tracking
   - Review status monitoring

2. Review Process
   - Admin review interface
   - Feedback submission
   - Revision tracking
   - Final approval workflow

#### 3.2.4 Disbursement Management
1. Processing
   - Fund release tracking
   - Payment record management
   - Budget allocation monitoring
   - Transaction history

2. Reporting
   - Generate financial reports
   - Track disbursement status
   - Export payment records
   - Budget utilization analysis

### 3.3 Non-Functional Requirements

#### 3.3.1 Performance
- Page load time < 3 seconds
- Support 100+ concurrent users
- File upload size limit: 20MB
- Database response time < 1 second

#### 3.3.2 Security
- Role-based access control
- Password encryption
- Session management
- CSRF protection
- Input validation
- File upload validation

#### 3.3.3 Reliability
- System uptime: 99.9%
- Daily database backups
- Error logging and monitoring
- Disaster recovery plan

#### 3.3.4 Usability
- Responsive design
- Intuitive navigation
- Clear error messages
- Help documentation
- Mobile compatibility

### 3.4 System Interfaces

#### 3.4.1 User Interfaces
- Modern web interface
- Dashboard for quick access
- Form-based data entry
- Document preview capability
- Status indicators

#### 3.4.2 Hardware Interfaces
- Support for standard web browsers
- Printer integration for reports
- File storage system

#### 3.4.3 Software Interfaces
- Database: MySQL 8.x
- Web Server: Apache
- Backend: Laravel Framework
- Frontend: React.js
- File Storage: Local/Cloud

## 4. Data Requirements

### 4.1 Database Requirements
- Relational database structure
- Data integrity constraints
- Backup and recovery
- Audit logging

### 4.2 Data Dictionary
Key entities and their attributes:

1. Users
   - ID (Primary Key)
   - Email
   - Password
   - Role
   - Status

2. Scholars
   - ID (Primary Key)
   - User ID (Foreign Key)
   - Academic Information
   - Program Details
   - Status

3. Fund Requests
   - ID (Primary Key)
   - Scholar ID (Foreign Key)
   - Request Type
   - Amount
   - Status
   - Documents

4. Manuscripts
   - ID (Primary Key)
   - Scholar ID (Foreign Key)
   - Title
   - Version
   - Status
   - Review Comments

5. Disbursements
   - ID (Primary Key)
   - Request ID (Foreign Key)
   - Amount
   - Date
   - Status

## 5. System Constraints

### 5.1 Technical Constraints
- Web browser compatibility
- Internet connectivity requirement
- File storage capacity
- Processing limitations

### 5.2 Business Rules
- Fund request approval workflow
- Document submission guidelines
- Payment processing rules
- User access policies

## 6. Appendix

### 6.1 Use Case Diagrams
[To be added: Visual representations of system interactions]

### 6.2 Data Flow Diagrams
[To be added: System data flow representations]

### 6.3 Entity Relationship Diagrams
[To be added: Database structure visualization]