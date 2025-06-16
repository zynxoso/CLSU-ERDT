<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Casts\EncryptedAttribute;
use Illuminate\Support\Facades\Log;

class ScholarProfile extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'birth_date',
        'contact_number',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'university',
        'department',
        'program',
        'degree_program',
        'status',
        'scholar_id',
        'major',
        'profile_photo',
        'is_verified',
        'verified_by',
        'verified_at',
        'admin_notes',
        'start_date',
        'expected_completion_date',
        'actual_completion_date',
        'bachelor_degree',
        'bachelor_university',
        'bachelor_graduation_year',
        'research_area',
        'research_title',
        'research_abstract',
        'notes',
        'degree_level',
        'enrollment_type',
        'study_time',
        'scholarship_duration',
        'government_id',
        'government_id_type',
        'tax_id',
        'medical_information',
        'emergency_contact',
        'emergency_contact_phone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'start_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        // Encrypted sensitive fields
        'contact_number' => EncryptedAttribute::class,
        'phone' => EncryptedAttribute::class,
        'address' => EncryptedAttribute::class,
        'government_id' => EncryptedAttribute::class,
        'tax_id' => EncryptedAttribute::class,
        'medical_information' => EncryptedAttribute::class,
        'emergency_contact' => EncryptedAttribute::class,
        'emergency_contact_phone' => EncryptedAttribute::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'government_id',
        'tax_id',
        'medical_information',
        'government_id_hash',
        'tax_id_hash',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Create hash values for searchable fields
            if (!empty($model->government_id)) {
                $model->government_id_hash = hash('sha256', $model->government_id);
            }

            if (!empty($model->tax_id)) {
                $model->tax_id_hash = hash('sha256', $model->tax_id);
            }

            // Log sensitive data access (without ID since model isn't saved yet)
            Log::info('Scholar profile being created with sensitive data', [
                'user_id' => auth()->id(),
                'has_government_id' => !empty($model->government_id),
                'has_tax_id' => !empty($model->tax_id),
                'timestamp' => now(),
            ]);
        });

        static::created(function ($model) {
            // Log after creation when ID is available
            Log::info('Scholar profile created with sensitive data', [
                'user_id' => auth()->id(),
                'scholar_profile_id' => $model->getKey(),
                'has_government_id' => !empty($model->government_id),
                'has_tax_id' => !empty($model->tax_id),
                'timestamp' => now(),
            ]);
        });

        static::updating(function ($model) {
            // Update hash values when original values change
            if ($model->isDirty('government_id')) {
                $model->government_id_hash = !empty($model->government_id)
                    ? hash('sha256', $model->government_id)
                    : null;

                Log::info('Government ID updated', [
                    'user_id' => auth()->id(),
                    'scholar_profile_id' => $model->getKey(),
                    'timestamp' => now(),
                ]);
            }

            if ($model->isDirty('tax_id')) {
                $model->tax_id_hash = !empty($model->tax_id)
                    ? hash('sha256', $model->tax_id)
                    : null;

                Log::info('Tax ID updated', [
                    'user_id' => auth()->id(),
                    'scholar_profile_id' => $model->getKey(),
                    'timestamp' => now(),
                ]);
            }

            // Log access to other sensitive fields
            $sensitiveFields = ['medical_information', 'contact_number', 'phone', 'address'];
            foreach ($sensitiveFields as $field) {
                if ($model->isDirty($field)) {
                    Log::info('Sensitive data updated', [
                        'user_id' => auth()->id(),
                        'scholar_profile_id' => $model->getKey(),
                        'field' => $field,
                        'timestamp' => now(),
                    ]);
                }
            }
        });
    }

    /**
     * Get the user that owns the scholar profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the fund requests for the scholar.
     */
    public function fundRequests()
    {
        return $this->hasMany(FundRequest::class);
    }

    /**
     * Get the manuscripts for the scholar.
     */
    public function manuscripts()
    {
        return $this->hasMany(Manuscript::class);
    }

    /**
     * Get the documents for the scholar.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the full name of the scholar.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name;
    }

    /**
     * Get the admin who verified the scholar profile.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Search scope for finding by government ID
     */
    public function scopeFindByGovernmentId($query, $governmentId)
    {
        Log::info('Government ID search performed', [
            'user_id' => auth()->id(),
            'timestamp' => now(),
        ]);

        return $query->where('government_id_hash', hash('sha256', $governmentId));
    }

    /**
     * Search scope for finding by tax ID
     */
    public function scopeFindByTaxId($query, $taxId)
    {
        Log::info('Tax ID search performed', [
            'user_id' => auth()->id(),
            'timestamp' => now(),
        ]);

        return $query->where('tax_id_hash', hash('sha256', $taxId));
    }

    /**
     * Get masked government ID for display purposes
     */
    public function getMaskedGovernmentIdAttribute()
    {
        $governmentId = $this->getAttribute('government_id');
        if (!$governmentId) {
            return null;
        }

        $length = strlen($governmentId);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        // Show only last 4 characters
        return str_repeat('*', $length - 4) . substr($governmentId, -4);
    }

    /**
     * Get masked tax ID for display purposes
     */
    public function getMaskedTaxIdAttribute()
    {
        $taxId = $this->getAttribute('tax_id');
        if (!$taxId) {
            return null;
        }

        $length = strlen($taxId);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        // Show only last 4 characters
        return str_repeat('*', $length - 4) . substr($taxId, -4);
    }

    /**
     * Get masked contact number for display purposes
     */
    public function getMaskedContactNumberAttribute()
    {
        $contactNumber = $this->getAttribute('contact_number');
        if (!$contactNumber) {
            return null;
        }

        $length = strlen($contactNumber);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        // Show only last 4 digits
        return str_repeat('*', $length - 4) . substr($contactNumber, -4);
    }

    /**
     * Log sensitive data access
     */
    public function logSensitiveDataAccess($field)
    {
        Log::info('Sensitive data accessed', [
            'user_id' => auth()->id(),
            'scholar_profile_id' => $this->getKey(),
            'field' => $field,
            'timestamp' => now(),
        ]);
    }
}
