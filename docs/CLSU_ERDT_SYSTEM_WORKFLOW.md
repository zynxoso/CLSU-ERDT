# 🎓 CLSU-ERDT System Workflow Documentation

## System Overview

The CLSU-ERDT (Central Luzon State University - Engineering Research and Development for Technology) system is a comprehensive, enterprise-grade Laravel-based web application designed to manage scholars, fund requests, and manuscript submissions for the Department of Agricultural and Biosystems Engineering (ABE) at CLSU. The system incorporates advanced security features, performance optimization, and comprehensive workflow management.

## 🎯 System Objectives

- **Scholar Management**: Comprehensive tracking and management of CLSU ABE scholars
- **Fund Request Processing**: Streamlined fund request submission and approval workflow
- **Manuscript Management**: Complete manuscript submission and review process
- **Security & Compliance**: Enterprise-grade security with audit trails
- **Performance Optimization**: High-performance system with advanced caching
- **User Experience**: Intuitive interface with real-time notifications

## User Roles & Access Levels

### 1. **Super Admin**
- **Highest level access** - can manage all system aspects
- Access to all administrative functions
- User management capabilities
- System configuration and audit controls

### 2. **Admin**
- **Administrative access** - manages scholars and requests
- Review and approve/reject fund requests
- Review and manage manuscript submissions
- Scholar profile management
- Generate reports and analytics

### 3. **Scholar** 
- **Limited access** - personal dashboard and submissions
- Create and manage fund requests
- Submit and track manuscripts
- Update personal profile information
- View notifications and status updates

---

## 🔐 Authentication System Flow

### **Step 1: Login Process**

#### **Scholar Login Flow:**
1. **Access Login Page**: Navigate to `/scholar-login`
2. **Authentication Validation**:
   - System validates email/password credentials
   - Checks if user role is 'scholar'
   - Verifies account is active (`is_active = true`)
   - Admin/Super Admin users are **blocked** from scholar login
3. **Security Checks**:
   - Rate limiting protection (5 attempts max)
   - Password expiration validation
   - Default password flag check
4. **Successful Login**:
   - Session creation and regeneration
   - Audit log entry created
   - Login timestamp and IP recorded
   - Redirect to `scholar.dashboard`

#### **Admin/Super Admin Login Flow:**
1. **Access Login Page**: Navigate to `/login`
2. **Authentication Validation**:
   - System validates email/password credentials
   - Checks if user role is 'admin' or 'super_admin'
   - Verifies account is active
   - Scholar users are **blocked** from admin login
3. **Security Features**:
   - Multi-guard authentication system
   - Database security context setting
   - Role-based access control (RBAC)
4. **Successful Login**:
   - Admin session establishment
   - Database security policies activated
   - Redirect to `admin.dashboard`

### **Step 2: Post-Login Security**
- **Password Expiration Check**: Users with expired passwords redirected to change password
- **Default Password Warning**: First-time users prompted to change default password
- **Session Security**: Enhanced session management with authentication middleware

---

## 👤 Scholar Profile Management

### **Profile Creation Workflow:**

#### **Step 1: Initial Profile Setup**
1. **New Scholar Account Creation** (Admin-initiated):
   - Admin creates user account with role 'scholar'
   - Default password: `CLSU-scholar123`
   - Account marked as `must_change_password = true`
   - Email verification required

2. **Scholar Profile Completion**:
   - Scholar logs in and prompted to complete profile
   - **Required Information**:
     - Personal details (name, birthdate, gender)
     - Contact information (phone, address)
     - Academic information (university, department, program)
     - Research details (area, title, abstract)

#### **Step 2: Profile Validation & Approval**
1. **Profile Review Process**:
   - Admin reviews submitted profile
   - Status progression: `New` → `Pending` → `Ongoing`
   - Verification of academic credentials
   - Document upload validation

2. **Status Management**:
   - **New**: Recently created profile
   - **Ongoing**: Active scholar in program
   - **On Extension**: Extended program duration
   - **Graduated**: Successfully completed program
   - **Terminated**: Program terminated
   - **Deferred Repayment**: Payment obligations deferred

---

## 💰 Fund Request Management Workflow

### **Step 1: Fund Request Creation (Scholar Side)**

1. **Access Fund Request System**:
   - Navigate to Scholar Dashboard → Fund Requests → Create New Request

2. **Request Type Selection**:
   - Choose from active request types (e.g., Research Materials, Conference, Equipment)
   - Each type has specific validation rules and amount limits

3. **Request Details Entry**:
   - **Amount**: Numerical validation with minimum/maximum limits
   - **Purpose**: Auto-populated based on request type
   - **Supporting Documents**: PDF upload (max 10MB)
   - **Additional Notes**: Optional remarks

4. **Draft vs. Submission**:
   - **Draft Status**: Can be edited and modified
   - **Submitted Status**: Locked for review, cannot be modified

5. **Document Upload & Security**:
   - CyberSweep security scanning
   - File validation (PDF only)
   - Storage in secured directories
   - Audit trail creation

### **Step 2: Fund Request Processing (Admin Side)**

#### **Admin Review Workflow:**
1. **Request Reception**:
   - **Notification System**: Admins receive email notifications for new submissions
   - **Dashboard Alert**: New requests appear in admin dashboard
   - **Duplicate Detection**: System flags potential duplicate requests

2. **Status Progression**:
   ```
   Draft → Submitted → Under Review → Approved/Rejected
   ```

3. **Review Process**:
   - **Under Review**: Admin claims request for review
   - **Document Verification**: Review supporting documents
   - **Amount Validation**: Check against program limits
   - **History Tracking**: Complete status change history

4. **Decision Making**:
   - **Approval Path**:
     - Admin marks as "Approved"
     - Scholar receives approval notification
     - Request moves to disbursement queue
   - **Rejection Path**:
     - Admin provides rejection reason
     - Scholar receives detailed rejection notice
     - Status change notification sent

### **Step 3: Disbursement Process**
1. **Disbursement Creation**:
   - Only approved requests eligible
   - Admin creates disbursement record
   - Financial tracking and reporting
   - Status updated to "Disbursed"

### **Step 4: Notification & Communication**
- **Real-time Status Updates**: Scholar receives notifications for all status changes
- **Email Notifications**: Configurable email alerts
- **In-App Notifications**: Dashboard notification system
- **Audit Trail**: Complete history of all actions and changes

---

## 📝 Manuscript Submission Workflow

### **Step 1: Manuscript Creation (Scholar Side)**

1. **Manuscript Submission Process**:
   - Navigate to Scholar Dashboard → Manuscripts → Create New

2. **Manuscript Information Entry**:
   - **Title**: Research paper title
   - **Abstract**: Research summary
   - **Manuscript Type**: Outline or Final
   - **Co-authors**: Additional researchers
   - **Keywords**: Research keywords
   - **Document Upload**: PDF manuscript file

3. **Draft Management**:
   - Initial status: `Draft`
   - Can edit and modify before submission
   - Auto-generated reference number (MS-XXXXX)

4. **Submission Process**:
   - Scholar submits for review
   - Status changes to `Submitted`
   - Manuscript locked from further editing
   - Admin notifications triggered

### **Step 2: Manuscript Review (Admin Side)**

#### **Review Status Progression:**
```
Draft → Submitted → Under Review → Revision Requested/Accepted/Rejected → Published
```

1. **Admin Review Process**:
   - **Document Review**: Examine manuscript content
   - **Academic Evaluation**: Assess research quality
   - **Status Management**: Update manuscript status
   - **Feedback Provision**: Add reviewer comments

2. **Review Decisions**:
   - **Under Review**: Active review in progress
   - **Revision Requested**: Requires scholar modifications
   - **Accepted**: Approved for publication
   - **Rejected**: Not suitable for publication
   - **Published**: Final published status

3. **Scholar Notification**:
   - Status change notifications
   - Detailed feedback communication
   - Revision request instructions

### **Step 3: Revision Cycle**
1. **Scholar Revisions**:
   - If revision requested, scholar can edit manuscript
   - Resubmission process
   - Review cycle continues until accepted/rejected

---

## 🔔 Notification System

### **Notification Types:**
1. **Fund Request Notifications**:
   - New request submitted
   - Status changes (approval/rejection)
   - Review completion alerts

2. **Manuscript Notifications**:
   - Submission confirmations
   - Review status updates
   - Revision requests

3. **Profile Notifications**:
   - Profile update confirmations
   - Admin profile modifications
   - Account status changes

4. **System Notifications**:
   - Security alerts
   - Password expiration warnings
   - Maintenance notices

### **Notification Delivery Methods:**
- **In-App Notifications**: Dashboard notification center
- **Email Notifications**: Configurable email alerts
- **Real-time Updates**: Live status updates on relevant pages

---

## 🔒 Security & Audit Features

### **Authentication Security:**
- **Multi-Guard System**: Separate authentication for scholars and admins
- **Rate Limiting**: Protection against brute force attacks
- **Session Management**: Secure session handling and regeneration
- **Password Policies**: Complexity requirements and expiration

### **Data Security:**
- **Row-Level Security**: Database policies restrict data access by role
- **Encrypted Attributes**: Sensitive data encryption
- **File Security**: Document scanning and secure storage
- **Audit Logging**: Comprehensive activity tracking

### **Access Control:**
- **Role-Based Permissions**: Granular access control
- **Middleware Protection**: Route-level security enforcement
- **Global Scopes**: Automatic data filtering by user role
- **Authorization Policies**: Fine-grained permission controls

---

## 📊 Dashboard & Analytics

### **Scholar Dashboard Features:**
- **Quick Statistics**: Fund request status, manuscript progress
- **Recent Activity**: Latest submissions and updates
- **Notifications Center**: Important alerts and messages
- **Profile Completion**: Progress tracking for profile setup

### **Admin Dashboard Features:**
- **System Overview**: Total scholars, requests, manuscripts
- **Pending Reviews**: Items requiring admin attention
- **Analytics Charts**: Visual data representation
- **Quick Actions**: Common administrative tasks

### **Reporting Capabilities:**
- **Fund Request Reports**: Financial tracking and analysis
- **Scholar Progress Reports**: Academic progress monitoring
- **System Usage Analytics**: Platform utilization metrics
- **Audit Reports**: Security and compliance tracking

---

## 🔄 Complete User Journey Examples

### **New Scholar Onboarding Flow:**
1. **Account Creation**: Admin creates scholar account
2. **First Login**: Scholar logs in with default password
3. **Password Change**: Forced password update
4. **Profile Completion**: Fill out comprehensive profile
5. **Account Activation**: Admin reviews and activates profile
6. **System Access**: Full access to fund requests and manuscript submission

### **Fund Request Complete Cycle:**
1. **Scholar**: Creates and submits fund request
2. **System**: Validates and sends admin notifications
3. **Admin**: Reviews request and makes decision
4. **System**: Notifies scholar of decision
5. **Admin**: Creates disbursement record (if approved)
6. **System**: Updates status to disbursed
7. **Reporting**: Transaction recorded for financial tracking

### **Manuscript Publication Journey:**
1. **Scholar**: Creates draft manuscript
2. **Scholar**: Submits for review
3. **Admin**: Reviews and requests revisions
4. **Scholar**: Makes revisions and resubmits
5. **Admin**: Accepts manuscript
6. **Admin**: Updates status to published
7. **System**: Final publication notification

---

## 🏗️ Technical Architecture

### Framework & Technologies
- **Backend**: Laravel 12.x (PHP 8.2+) with advanced security features
- **Frontend**: Blade templating with Tailwind CSS 3.x, Alpine.js, Bootstrap 5.3
- **Database**: MySQL/PostgreSQL with optimized Eloquent ORM
- **Caching**: Redis for session management, rate limiting, and performance caching
- **Queue System**: Redis-based job queues for background processing
- **File Storage**: Secure local storage with CyberSweep scanning
- **Authentication**: Laravel Fortify with multi-factor authentication support
- **Security**: Multi-layered DDoS protection and rate limiting
- **Build Tools**: Vite 6.x for modern asset compilation
- **Testing**: PHPUnit with comprehensive test coverage

### Key Laravel Features Utilized
- **Eloquent ORM**: Advanced database relationships and optimized queries
- **Middleware**: Authentication, authorization, rate limiting, and security scanning
- **Events & Listeners**: Real-time notification triggers and comprehensive audit logging
- **Jobs & Queues**: Background email processing and file scanning
- **Policies**: Fine-grained authorization control with role-based permissions
- **Form Requests**: Advanced input validation, sanitization, and security checks
- **Notifications**: Multi-channel email and in-app notifications
- **File Storage**: Secure document management with virus scanning
- **Livewire**: Dynamic interfaces for real-time user interactions
- **API Resources**: RESTful API endpoints with rate limiting

### 🛡️ Security Features
- **CyberSweep**: Advanced file scanning and threat detection
- **Rate Limiting**: 8-tier rate limiting with IP blocking
- **DDoS Protection**: Multi-layered attack prevention
- **Input Validation**: Comprehensive XSS and SQL injection prevention
- **Audit Logging**: Complete activity tracking and compliance
- **Encryption**: Data encryption at rest and in transit
- **Session Security**: Secure session management with Redis

### 📊 Performance Optimizations
- **Redis Caching**: High-performance data caching
- **Database Indexing**: Optimized database queries
- **Asset Optimization**: Minified and compressed assets
- **Lazy Loading**: Efficient data loading strategies
- **Queue Processing**: Background job processing
- **CDN Ready**: Prepared for content delivery networks

---

This documentation provides a comprehensive overview of the CLSU-ERDT system workflow, from initial user authentication through complex multi-step processes like fund requests and manuscript submissions. The system emphasizes security, transparency, and efficient workflow management while maintaining detailed audit trails and user notifications throughout all processes.
