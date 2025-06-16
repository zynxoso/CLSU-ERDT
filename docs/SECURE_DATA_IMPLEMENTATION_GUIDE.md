# CLSU-ERDT Secure Data Storage Implementation Guide

## Overview

This guide explains the secure data storage implementation for the CLSU-ERDT Scholar Management System. The implementation provides comprehensive protection for sensitive data including personal information, financial data, and documents.

## Features Implemented

### 1. Field-Level Encryption
- **Contact numbers and phone numbers** - Encrypted using Laravel's built-in encryption
- **Addresses** - Full address information encrypted
- **Government IDs** - Social Security, Driver's License, etc.
- **Tax identification numbers** - TIN and other tax-related IDs
- **Medical information** - Health records and medical notes
- **Emergency contact information** - Contact details for emergencies
- **Financial data** - Bank account numbers, routing numbers, account holder names

### 2. Searchable Hashing
- **Government ID hash** - Allows searching without exposing the actual ID
- **Tax ID hash** - Enables tax ID lookups while maintaining privacy
- **Tax identification hash** - For fund request tax ID searches

### 3. File Encryption
- **Document encryption** - All uploaded files are encrypted before storage
- **Secure file validation** - Comprehensive security scanning before encryption
- **Encrypted file retrieval** - Secure download with automatic decryption

### 4. Audit Logging
- **Data access logging** - All sensitive data access is logged
- **Search operation logging** - Government ID and Tax ID searches are tracked
- **File operation logging** - File uploads, downloads, and deletions are logged

### 5. Data Masking
- **Masked display** - Sensitive data shows only last 4 characters
- **Safe serialization** - Sensitive fields are hidden from JSON output

## Usage Examples

### Working with Scholar Profiles

```php
use App\Models\ScholarProfile;

// Create a scholar profile with encrypted data
$profile = ScholarProfile::create([
    'user_id' => $user->id,
    'first_name' => 'John',
    'last_name' => 'Doe',
    'contact_number' => '09123456789',        // Will be encrypted
    'address' => '123 Main St, City',        // Will be encrypted
    'government_id' => '1234567890123',      // Will be encrypted + hashed
    'government_id_type' => 'SSS',
    'tax_id' => 'TIN123456789',             // Will be encrypted + hashed
    'medical_information' => 'No allergies', // Will be encrypted
    // ... other fields
]);

// The government_id_hash and tax_id_hash are automatically created

// Search by government ID (uses hash for privacy)
$scholar = ScholarProfile::findByGovernmentId('1234567890123')->first();

// Search by tax ID (uses hash for privacy)
$scholar = ScholarProfile::findByTaxId('TIN123456789')->first();

// Access encrypted data (automatically decrypted)
echo $scholar->contact_number;  // Returns decrypted value
echo $scholar->government_id;   // Returns decrypted value

// Display masked data for UI
echo $scholar->masked_government_id;    // Returns "********0123"
echo $scholar->masked_contact_number;   // Returns "******6789"

// Log sensitive data access
$scholar->logSensitiveDataAccess('government_id');
```

### Working with Fund Requests

```php
use App\Models\FundRequest;

// Create a fund request with encrypted financial data
$fundRequest = FundRequest::create([
    'scholar_profile_id' => $profile->id,
    'amount' => 50000.00,
    'purpose' => 'Research materials',
    'bank_account_number' => '1234567890',      // Will be encrypted
    'bank_name' => 'Test Bank',                 // Will be encrypted
    'account_holder_name' => 'John Doe',        // Will be encrypted
    'routing_number' => '987654321',            // Will be encrypted
    'tax_identification_number' => 'TIN123',   // Will be encrypted + hashed
    'amount_breakdown' => 'Books: 20000',       // Will be encrypted
    // ... other fields
]);

// Search by tax identification number
$request = FundRequest::findByTaxIdentificationNumber('TIN123')->first();

// Access encrypted financial data
echo $fundRequest->bank_account_number;  // Returns decrypted value

// Display masked financial data
echo $fundRequest->masked_bank_account_number;  // Returns "******7890"

// Log financial data access
$fundRequest->logFinancialDataAccess('bank_account_number');
```

### Working with Encrypted Files

```php
use App\Services\FileSecurityService;

$fileService = new FileSecurityService();

// Store an encrypted file
$result = $fileService->storeEncryptedFile(
    $uploadedFile,           // UploadedFile instance
    'secure_documents',      // Storage path
    auth()->id()            // User ID for logging
);

// Result contains:
// - success: boolean
// - original_name: string
// - stored_path: string
// - mime_type: string
// - size: integer
// - file_info: array

// Retrieve and decrypt a file
$decryptedContent = $fileService->retrieveEncryptedFile(
    $result['stored_path'],
    auth()->id()
);

// Download an encrypted file (returns Response)
return $fileService->downloadEncryptedFile(
    $result['stored_path'],
    $result['original_name'],
    auth()->id()
);

// Delete an encrypted file
$deleted = $fileService->deleteEncryptedFile(
    $result['stored_path'],
    auth()->id()
);

// Validate file integrity
$isValid = $fileService->validateFileIntegrity(
    $result['stored_path'],
    $expectedHash,
    auth()->id()
);
```

## Database Schema

### Scholar Profiles Table
```sql
-- Encrypted fields (stored as TEXT)
government_id TEXT NULL,
tax_id TEXT NULL,
medical_information TEXT NULL,
contact_number TEXT NULL,
phone TEXT NULL,
address TEXT NULL,
emergency_contact TEXT NULL,
emergency_contact_phone TEXT NULL,

-- Hash fields for searching (stored as VARCHAR with INDEX)
government_id_hash VARCHAR(255) NULL INDEX,
tax_id_hash VARCHAR(255) NULL INDEX,
```

### Fund Requests Table
```sql
-- Encrypted financial fields (stored as TEXT)
bank_account_number TEXT NULL,
bank_name TEXT NULL,
account_holder_name TEXT NULL,
routing_number TEXT NULL,
tax_identification_number TEXT NULL,
amount_breakdown TEXT NULL,

-- Hash field for searching (stored as VARCHAR with INDEX)
tax_identification_hash VARCHAR(255) NULL INDEX,
```

## Security Best Practices

### 1. Environment Configuration
Ensure your `.env` file has a strong application key:
```env
APP_KEY=base64:your-32-character-random-string-here
```

### 2. Database Security
- Use encrypted connections to your database
- Implement database-level encryption at rest
- Restrict database user permissions
- Regular security audits

### 3. Application Security
- Always validate input before encryption
- Use HTTPS for all communications
- Implement proper access controls
- Regular security updates

### 4. Key Management
- Rotate encryption keys regularly
- Store keys securely (consider using Laravel's key rotation features)
- Never commit keys to version control
- Use environment-specific keys

## Monitoring and Auditing

### Log Analysis
All sensitive data operations are logged. Monitor these logs for:
- Unusual access patterns
- Failed decryption attempts
- Unauthorized search operations
- File access anomalies

### Log Locations
- **Application logs**: `storage/logs/laravel.log`
- **Sensitive data access**: Search for "Sensitive data accessed"
- **Search operations**: Search for "search performed"
- **File operations**: Search for "File stored with encryption"

### Sample Log Entries
```
[2025-06-12 02:48:00] local.INFO: Sensitive data accessed {"user_id":1,"scholar_profile_id":5,"field":"government_id","timestamp":"2025-06-12 02:48:00"}

[2025-06-12 02:48:00] local.INFO: Government ID search performed {"user_id":1,"timestamp":"2025-06-12 02:48:00"}

[2025-06-12 02:48:00] local.INFO: File stored with encryption {"original_name":"document.pdf","stored_path":"secure_documents/document_1234567890_abc123.pdf","user_id":1,"file_size":1024,"timestamp":"2025-06-12 02:48:00"}
```

## Performance Considerations

### 1. Encryption Overhead
- Encryption/decryption adds computational overhead
- Consider caching decrypted values for frequently accessed data
- Use database indexing on hash fields for fast searches

### 2. Storage Requirements
- Encrypted data requires more storage space
- Hash fields add additional storage overhead
- Plan for approximately 30-50% increase in storage requirements

### 3. Search Performance
- Hash-based searches are very fast (indexed)
- Avoid full-text search on encrypted fields
- Use hash fields for all search operations

## Troubleshooting

### Common Issues

1. **Decryption Failures**
   - Check if APP_KEY has changed
   - Verify data was encrypted with current key
   - Check for database corruption

2. **Search Not Working**
   - Verify hash fields are properly indexed
   - Check if hash generation is consistent
   - Ensure search uses exact hash matching

3. **File Encryption Issues**
   - Check storage permissions
   - Verify file validation passes
   - Check available disk space

### Error Handling
The implementation includes comprehensive error handling:
- Failed encryption operations are logged
- Decryption failures return null values
- File operations include detailed error messages

## Migration and Deployment

### Running Migrations
```bash
# Run the security field migrations
php artisan migrate --path=database/migrations/2025_06_12_024808_add_security_fields_to_scholar_profiles.php
php artisan migrate --path=database/migrations/2025_06_12_024850_add_security_fields_to_fund_requests.php
```

### Data Migration
If you have existing data that needs to be encrypted:

1. **Backup your database** before running any migration scripts
2. Create a migration script to encrypt existing data
3. Test thoroughly in a staging environment
4. Plan for downtime during the migration

### Example Data Migration Script
```php
// Encrypt existing scholar profile data
ScholarProfile::chunk(100, function ($profiles) {
    foreach ($profiles as $profile) {
        if ($profile->contact_number && !$profile->government_id_hash) {
            // This will trigger the encryption and hashing
            $profile->save();
        }
    }
});
```

## Compliance and Regulations

This implementation helps meet various compliance requirements:

- **Data Privacy Laws** (GDPR, CCPA): Personal data encryption
- **Financial Regulations**: Secure handling of financial information
- **Healthcare Compliance** (HIPAA): Medical information protection
- **Educational Records** (FERPA): Student information security

## Support and Maintenance

### Regular Tasks
1. **Monitor logs** for security events
2. **Update dependencies** regularly
3. **Test backup and recovery** procedures
4. **Review access patterns** for anomalies
5. **Rotate encryption keys** as per policy

### Security Updates
- Keep Laravel framework updated
- Monitor security advisories
- Update encryption libraries
- Review and update security policies

## Conclusion

This secure data storage implementation provides comprehensive protection for sensitive data in the CLSU-ERDT system. It balances security with usability, ensuring that sensitive information is protected while maintaining the functionality needed for the scholarship management system.

For questions or issues, refer to the Laravel documentation on encryption and the specific implementation files in the codebase. 
