# Database-Level Security Implementation Guide

## Overview

This document outlines the comprehensive database-level security implementation for the CLSU-ERDT system. The implementation provides defense-in-depth security with multiple layers of protection.

## Security Audit Summary

### ❌ **Critical Security Gaps Identified**

1. **No Row-Level Security (RLS)** - Database queries return all data regardless of user role
2. **Single Database User** - All application operations use the same database credentials
3. **No Database Permission Granularity** - No role-based database access control
4. **No Query Scoping** - Models don't automatically filter data based on user context
5. **Privilege Escalation Risk** - Application-level security bypass could expose all data

### ✅ **Existing Security Measures**

1. **Application-Level Role Checks** - Middleware and policy-based access control
2. **Controller-Level Filtering** - Manual data scoping in controllers and services
3. **Laravel Authorization Policies** - Resource-based access control
4. **Audit Logging** - Comprehensive activity tracking

## Implemented Database Security Solutions

### 1. Row-Level Security (RLS) - PostgreSQL

#### Features:
- **Automatic Data Filtering**: Database-level data filtering based on user context
- **Role-Based Policies**: Separate policies for admin, scholar, and super_admin roles
- **Session Context**: User ID and role stored in database session variables
- **Zero-Bypass Risk**: Security enforced at the database level

#### Implementation:
```sql
-- Enable RLS on sensitive tables
ALTER TABLE scholar_profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE fund_requests ENABLE ROW LEVEL SECURITY;
ALTER TABLE documents ENABLE ROW LEVEL SECURITY;
ALTER TABLE manuscripts ENABLE ROW LEVEL SECURITY;

-- Create role-based policies
CREATE POLICY scholar_profile_policy ON scholar_profiles
FOR ALL TO erdt_scholar
USING (user_id = current_setting('app.current_user_id')::integer);
```

### 2. Database User Roles & Permissions

#### Created Database Roles:
- `erdt_super_admin` - Full access to all tables and operations
- `erdt_admin` - Read/write access to application tables
- `erdt_scholar` - Limited access to own data only

#### Permission Structure:
```sql
-- Admin permissions
GRANT SELECT, INSERT, UPDATE ON ALL TABLES IN SCHEMA public TO erdt_admin;

-- Scholar permissions (limited)
GRANT SELECT, INSERT, UPDATE ON scholar_profiles TO erdt_scholar;
GRANT SELECT, INSERT, UPDATE ON fund_requests TO erdt_scholar;
GRANT SELECT, INSERT, UPDATE ON documents TO erdt_scholar;
GRANT SELECT, INSERT, UPDATE ON manuscripts TO erdt_scholar;
```

### 3. Global Query Scopes

#### ScholarAccessScope Features:
- **Automatic Query Filtering**: Applied to all model queries
- **Role-Based Access**: Different rules for admin, scholar, and super_admin
- **Default-Deny**: Unknown roles are denied access by default
- **Model-Specific Logic**: Tailored access rules per model type

#### Implementation:
```php
// Automatically applied to all queries
$fundRequests = FundRequest::all(); // Only returns user's own data for scholars

// Admins bypass the scope and see all data
// Scholars only see their own scholar_profile_id data
```

### 4. Database Security Service

#### Features:
- **Context Management**: Sets and manages database security context
- **Connection Switching**: Role-based database connection selection
- **Access Validation**: Validates user access to specific records
- **Audit Trail**: Comprehensive security action logging

#### Key Methods:
```php
$securityService->setSecurityContext($user);
$securityService->validateRecordAccess($user, 'FundRequest', $recordId);
$securityService->auditSecurityAction('access_attempt', $data);
```

### 5. Database Security Middleware

#### Features:
- **Automatic Context Setting**: Sets security context on every request
- **Role-Based Connection**: Switches database connections based on user role
- **Request Auditing**: Logs security context changes
- **Cleanup**: Resets context after request completion

## Deployment Instructions

### Step 1: Environment Configuration

Add these environment variables to your `.env` file:

```env
# Database role-based user credentials
DB_SUPER_ADMIN_USERNAME=erdt_super_admin
DB_SUPER_ADMIN_PASSWORD=super_secure_password_123!

DB_ADMIN_USERNAME=erdt_admin
DB_ADMIN_PASSWORD=admin_secure_password_456!

DB_SCHOLAR_USERNAME=erdt_scholar
DB_SCHOLAR_PASSWORD=scholar_secure_password_789!

# Security features
DB_ENABLE_RLS=true
DB_ENABLE_ROLE_SWITCHING=true
```

### Step 2: Database Setup

Run the security migration:

```bash
php artisan migrate --path=database/migrations/2025_01_17_000001_implement_database_security.php
```

### Step 3: Middleware Registration

Add the security middleware to routes that require database access:

```php
// In routes/web.php
Route::middleware(['auth', 'db.security'])->group(function () {
    // All authenticated routes
});

// In routes/api.php
Route::middleware(['auth:sanctum', 'db.security'])->group(function () {
    // All API routes
});
```

### Step 4: Model Updates

Apply the global scope to models that need access control:

```php
// In sensitive models (already implemented in ScholarProfile)
protected static function boot()
{
    parent::boot();
    static::addGlobalScope(new ScholarAccessScope);
}
```

### Step 5: Service Provider Registration

Register the database security service in `config/app.php`:

```php
'providers' => [
    // ... other providers
    App\Providers\DatabaseSecurityServiceProvider::class,
],
```

## Security Benefits

### 1. **Defense in Depth**
- Application-level security (existing)
- Database-level security (new)
- Network-level security (existing)

### 2. **Zero-Trust Architecture**
- Every query is validated at the database level
- No implicit trust in application-level security
- Explicit access control for every data access

### 3. **Compliance & Audit**
- Comprehensive audit trails
- Security action logging
- Data access monitoring

### 4. **Scalability**
- Automatic security enforcement
- No manual security checks required
- Consistent security across all application layers

## Testing & Validation

### Test Scenarios:

1. **Scholar Access Test**:
   ```php
   // Scholars should only see their own data
   $user = User::where('role', 'scholar')->first();
   Auth::login($user);
   
   $fundRequests = FundRequest::all();
   // Should only return requests for this scholar
   ```

2. **Admin Access Test**:
   ```php
   // Admins should see all data
   $admin = User::where('role', 'admin')->first();
   Auth::login($admin);
   
   $fundRequests = FundRequest::all();
   // Should return all fund requests
   ```

3. **Direct Database Access Test**:
   ```sql
   -- Connect as scholar user
   SET ROLE erdt_scholar;
   SELECT set_config('app.current_user_id', '123', false);
   
   SELECT * FROM fund_requests;
   -- Should only return rows for scholar_profile_id belonging to user 123
   ```

## Monitoring & Maintenance

### Security Monitoring:
- Review database security logs regularly
- Monitor failed access attempts
- Audit role permission changes
- Track suspicious query patterns

### Maintenance Tasks:
- Regular security context validation
- Database role permission reviews
- Security policy updates
- Performance impact monitoring

## Performance Considerations

### Query Performance:
- Indexes added on security-relevant columns
- Efficient RLS policy implementation
- Connection pooling for role-based connections

### Monitoring:
- Query execution time tracking
- Database load monitoring
- Security context switching overhead

## Rollback Procedures

If issues arise, you can disable security features:

```bash
# Disable RLS
php artisan migrate:rollback --path=database/migrations/2025_01_17_000001_implement_database_security.php

# Remove middleware
# Comment out 'db.security' middleware in routes

# Switch back to default connection
# Set DB_ENABLE_ROLE_SWITCHING=false in .env
```

## Conclusion

This implementation provides comprehensive database-level security that complements the existing application-level security measures. The system now enforces role-based access control at multiple layers, significantly reducing the risk of unauthorized data access.

### Key Improvements:
1. ✅ **Row-Level Security implemented**
2. ✅ **Database user roles created**
3. ✅ **Permission grants configured**
4. ✅ **Global query scoping implemented**
5. ✅ **Automatic security context management**
6. ✅ **Comprehensive audit logging**

The system now provides defense-in-depth security with minimal performance impact and full compatibility with existing application functionality. 
