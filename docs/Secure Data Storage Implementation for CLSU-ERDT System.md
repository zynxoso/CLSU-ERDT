# Secure Data Storage Implementation for CLSU-ERDT System

## 1. Sensitive Data Field Analysis

Based on the CLSU-ERDT Scholar Management System structure, here are the key sensitive data fields that require protection:

### Scholar Personal Information
| Field | Sensitivity | Protection Method | Rationale |
|-------|-------------|-------------------|-----------|
| Password | Critical | Hashing (bcrypt) | One-way transformation, never needs to be retrieved in original form |
| Government ID Numbers | Critical | Encryption + Hash for search | Needs to be retrieved occasionally but also searched |
| Full Name | Medium | Store as plaintext | Needed for display and search, but implement access controls |
| Email | Medium | Store as plaintext + access controls | Needed for communication and login |
| Phone Number | Medium | Encryption | Needs to be retrieved but not frequently searched |
| Home Address | High | Encryption | Personal data that needs protection but must be retrievable |
| Birth Date | Medium | Encryption | Sensitive but may need to be retrieved |
| Medical Information | Critical | Encryption | Highly sensitive but needs to be retrieved for verification |

### Academic and Financial Data
| Field | Sensitivity | Protection Method | Rationale |
|-------|-------------|-------------------|-----------|
| Bank Account Numbers | Critical | Encryption | Must be retrievable but highly sensitive |
| Tax Identification | Critical | Encryption + Hash for search | May need searching and retrieval |
| Fund Request Details | High | Encryption for amounts | Financial data needs protection |
| Academic Records | Medium | Access controls | Needed for frequent access but sensitive |
| Research Manuscripts | Medium | File encryption + access controls | Intellectual property protection |

## 2. Implementation Approach for Laravel

### 2.1 Password Hashing (Already Implemented in Laravel)

Laravel's authentication system already handles password hashing securely:

```php
// This happens automatically with Laravel's authentication
use Illuminate\Support\Facades\Hash;

// When creating a user
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password), // Secure hashing
]);

// When verifying a password
if (Hash::check($request->password, $user->password)) {
    // Password is correct
}
```

### 2.2 Implementing Field Encryption

For sensitive data that needs to be retrieved in its original form, use Laravel's encryption:

#### Step 1: Create a model cast for encrypted attributes

```php
// app/Casts/EncryptedAttribute.php
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;

class EncryptedAttribute implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value ? Crypt::encryptString($value) : null;
    }
}
```

#### Step 2: Apply the cast to your models

```php
// app/Models/ScholarProfile.php
namespace App\Models;

use App\Casts\EncryptedAttribute;
use Illuminate\Database\Eloquent\Model;

class ScholarProfile extends Model
{
    protected $casts = [
        'phone_number' => EncryptedAttribute::class,
        'home_address' => EncryptedAttribute::class,
        'birth_date' => EncryptedAttribute::class,
        'medical_information' => EncryptedAttribute::class,
    ];
    
    // Rest of your model
}

// app/Models/FundRequest.php
namespace App\Models;

use App\Casts\EncryptedAttribute;
use Illuminate\Database\Eloquent\Model;

class FundRequest extends Model
{
    protected $casts = [
        'bank_account_number' => EncryptedAttribute::class,
        'tax_id' => EncryptedAttribute::class,
        'amount_details' => EncryptedAttribute::class,
    ];
    
    // Rest of your model
}
```

### 2.3 Implementing Searchable Hashing

For fields that need to be both protected and searchable, implement a dual approach with both encryption and hashing:

#### Step 1: Update your database schema

```php
// Create a migration
php artisan make:migration add_hash_fields_to_scholar_profiles_table

// In the migration file
public function up()
{
    Schema::table('scholar_profiles', function (Blueprint $table) {
        $table->string('government_id_hash')->nullable()->index();
        $table->string('tax_id_hash')->nullable()->index();
    });
}
```

#### Step 2: Implement automatic hashing in your model

```php
// app/Models/ScholarProfile.php
protected static function boot()
{
    parent::boot();
    
    static::creating(function ($model) {
        // Hash for searching
        if (isset($model->government_id)) {
            $model->government_id_hash = hash('sha256', $model->government_id);
        }
        
        if (isset($model->tax_id)) {
            $model->tax_id_hash = hash('sha256', $model->tax_id);
        }
    });
    
    static::updating(function ($model) {
        // Update hashes if original values change
        if ($model->isDirty('government_id')) {
            $model->government_id_hash = hash('sha256', $model->government_id);
        }
        
        if ($model->isDirty('tax_id')) {
            $model->tax_id_hash = hash('sha256', $model->tax_id);
        }
    });
}
```

#### Step 3: Create search methods using the hashed values

```php
// app/Models/ScholarProfile.php
public function scopeFindByGovernmentId($query, $governmentId)
{
    return $query->where('government_id_hash', hash('sha256', $governmentId));
}

public function scopeFindByTaxId($query, $taxId)
{
    return $query->where('tax_id_hash', hash('sha256', $taxId));
}
```

### 2.4 File Encryption for Manuscripts and Documents

For document and manuscript files:

```php
// app/Services/FileSecurityService.php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class FileSecurityService
{
    public function storeEncryptedFile($file, $path)
    {
        // Generate a unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $fullPath = $path . '/' . $filename;
        
        // Read file content
        $content = file_get_contents($file->getRealPath());
        
        // Encrypt the content
        $encryptedContent = Crypt::encrypt($content);
        
        // Store the encrypted content
        Storage::put($fullPath, $encryptedContent);
        
        return [
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $fullPath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }
    
    public function retrieveEncryptedFile($path)
    {
        // Get encrypted content
        $encryptedContent = Storage::get($path);
        
        // Decrypt the content
        return Crypt::decrypt($encryptedContent);
    }
}
```

## 3. Complete Implementation Example

Here's a complete implementation example for the ScholarProfile model:

```php
<?php

namespace App\Models;

use App\Casts\EncryptedAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ScholarProfile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone_number',
        'home_address',
        'birth_date',
        'government_id',
        'government_id_type',
        'tax_id',
        'medical_information',
        'academic_status',
    ];
    
    protected $casts = [
        'phone_number' => EncryptedAttribute::class,
        'home_address' => EncryptedAttribute::class,
        'birth_date' => EncryptedAttribute::class,
        'government_id' => EncryptedAttribute::class,
        'tax_id' => EncryptedAttribute::class,
        'medical_information' => EncryptedAttribute::class,
    ];
    
    protected $hidden = [
        'government_id',
        'tax_id',
        'medical_information',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Create hash values for searchable fields
            if (isset($model->government_id)) {
                $model->government_id_hash = hash('sha256', $model->government_id);
            }
            
            if (isset($model->tax_id)) {
                $model->tax_id_hash = hash('sha256', $model->tax_id);
            }
        });
        
        static::updating(function ($model) {
            // Update hash values when original values change
            if ($model->isDirty('government_id')) {
                $model->government_id_hash = hash('sha256', $model->government_id);
            }
            
            if ($model->isDirty('tax_id')) {
                $model->tax_id_hash = hash('sha256', $model->tax_id);
            }
        });
    }
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Search scopes
    public function scopeFindByGovernmentId($query, $governmentId)
    {
        return $query->where('government_id_hash', hash('sha256', $governmentId));
    }
    
    public function scopeFindByTaxId($query, $taxId)
    {
        return $query->where('tax_id_hash', hash('sha256', $taxId));
    }
    
    // Accessor for masked government ID (for display purposes)
    public function getMaskedGovernmentIdAttribute()
    {
        if (!$this->government_id) {
            return null;
        }
        
        $id = $this->government_id;
        $length = strlen($id);
        
        // Show only last 4 characters
        return str_repeat('*', $length - 4) . substr($id, -4);
    }
    
    // Accessor for masked tax ID (for display purposes)
    public function getMaskedTaxIdAttribute()
    {
        if (!$this->tax_id) {
            return null;
        }
        
        $id = $this->tax_id;
        $length = strlen($id);
        
        // Show only last 4 characters
        return str_repeat('*', $length - 4) . substr($id, -4);
    }
}
```

## 4. Security Best Practices for Data Storage

### 4.1 Database-Level Security

1. **Use Prepared Statements**: Laravel's Eloquent ORM and Query Builder already use prepared statements to prevent SQL injection.

2. **Database Encryption at Rest**: Configure your database to use encryption at rest:
   ```
   # For MySQL in my.cnf
   [mysqld]
   innodb_encrypt_tables=ON
   innodb_encrypt_log=ON
   ```

3. **Limit Database User Permissions**: Create a dedicated database user for your application with only the necessary permissions.

4. **Regular Backups**: Implement encrypted backups of your database.

### 4.2 Application-Level Security

1. **Environment Variables**: Store encryption keys and sensitive configuration in environment variables, not in code.
   ```
   # .env file
   APP_KEY=base64:your-secure-key-here
   ```

2. **Key Rotation**: Implement a key rotation policy for encryption keys.

3. **Input Validation**: Always validate and sanitize user inputs before processing.
   ```php
   $request->validate([
       'government_id' => 'required|string|max:20',
       'tax_id' => 'required|string|max:15',
   ]);
   ```

4. **Limit Data Exposure**: Use Laravel's `$hidden` property to prevent sensitive data from being serialized.

5. **Audit Logging**: Log all access to sensitive data.
   ```php
   // When accessing sensitive data
   Log::info('Sensitive data accessed', [
       'user_id' => auth()->id(),
       'data_type' => 'government_id',
       'scholar_id' => $scholar->id,
       'timestamp' => now(),
   ]);
   ```

### 4.3 Transport-Level Security

1. **Force HTTPS**: Ensure all traffic uses HTTPS.
   ```php
   // In AppServiceProvider.php
   public function boot()
   {
       if ($this->app->environment('production')) {
           URL::forceScheme('https');
       }
   }
   ```

2. **Set Secure Cookies**: Configure secure cookies in `config/session.php`.
   ```php
   'secure' => env('SESSION_SECURE_COOKIE', true),
   'same_site' => 'lax',
   ```

### 4.4 Monitoring and Maintenance

1. **Regular Security Audits**: Conduct periodic security audits of your data storage practices.

2. **Update Dependencies**: Keep Laravel and all packages updated to patch security vulnerabilities.

3. **Monitor for Unusual Activity**: Implement monitoring for unusual database access patterns.

## 5. Differences from CyberSweep

CyberSweep typically focuses on broader cybersecurity services including:

1. **Threat Intelligence**: Monitoring for emerging threats
2. **Vulnerability Scanning**: Identifying system vulnerabilities
3. **Incident Response**: Handling security breaches
4. **Security Assessments**: Comprehensive security evaluations

The data protection approach outlined in this document is more focused on:

1. **Data-Centric Security**: Protecting specific data elements
2. **Encryption and Hashing**: Technical implementation of data protection
3. **Application-Level Controls**: Security within your Laravel application

While CyberSweep provides broader security services, this approach is complementary and focuses specifically on securing sensitive data within your application's database.

## 6. Implementation Checklist

1. [ ] Identify all sensitive data fields in your database
2. [ ] Create necessary database migrations to add hash fields
3. [ ] Implement encryption casts for sensitive fields
4. [ ] Update models with automatic hashing functionality
5. [ ] Create search methods using hashed values
6. [ ] Implement file encryption for documents and manuscripts
7. [ ] Update controllers to use the new secure methods
8. [ ] Add audit logging for sensitive data access
9. [ ] Test thoroughly with sample data
10. [ ] Deploy changes with proper key management

## 7. Conclusion

This implementation plan provides a comprehensive approach to securing sensitive data in your CLSU-ERDT Scholar Management System using Laravel's built-in features and some custom extensions. By properly implementing hashing for non-retrievable data and encryption for data that needs to be retrieved, you can significantly enhance the security of your system while maintaining functionality.
