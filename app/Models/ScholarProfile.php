<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Casts\EncryptedAttribute;
use Illuminate\Support\Facades\Log;
use App\Models\Scopes\ScholarAccessScope;
use Illuminate\Support\Facades\Auth;

/**
 * Class ScholarProfile
 * 
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $suffix
 * @property string|null $gender
 * @property \Carbon\Carbon|null $birth_date
 * @property string|null $contact_number
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $street
 * @property string|null $village
 * @property string|null $zipcode
 * @property string|null $district
 * @property string|null $region
 * @property string|null $department
 * @property string|null $course_completed
 * @property string|null $university_graduated
 * @property string|null $entry_type
 * @property string|null $intended_degree
 * @property string|null $thesis_dissertation_title
 * @property int|null $units_required
 * @property int|null $units_earned_prior

 * @property \Carbon\Carbon|null $start_date
 * @property \Carbon\Carbon|null $actual_completion_date

 * @property string|null $study_time
 * @property string|null $scholarship_duration


 * @property string|null $profile_photo
 * @property bool $is_verified
 * @property \Carbon\Carbon|null $verified_at
 * @property int|null $verified_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarProfile query()
 * @method int getKey()
 */
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
        // Personal Information (Required)
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'gender', // Male, Female, Other, Prefer not to say
        'birth_date', // YYYY-MM-DD format
        'contact_number',
        // Home Address Structure
        'street',
        'village',
        'town',
        'province',
        'zipcode',
        'district', // 1-6 or Lone
        'region', // 1-3, 4A, 4B, 5-12, CARAGA, ARMM, CAR, NCR
        'country',
        // Educational Background
        'course_completed', // e.g., BS Physics, MS Physics
        'university_graduated', // Full university name with campus/branch
        // Application Details
        'entry_type', // NEW or LATERAL
        'intended_degree', // e.g., MS Physics, PHD Physics
        'level', // MS, PHD
        'intended_university', // Full name with campus/branch
        'department', // Department/College
        'thesis_dissertation_title',
        // Academic Load (for Lateral Entrants)
        'units_required',
        'units_earned_prior',
        // Scholarship Details
        'start_date', // Scholarship start date

        'study_time', // Full-time, Part-time
        'scholarship_duration', // Duration in months
        // System fields
        'profile_photo',
        'is_verified',
        'verified_by',
        'verified_at',
        'actual_completion_date',
        'notes',
        'scholar_status',

        // Legacy fields (for backward compatibility)


        // Notification tracking
        'last_notified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'start_date' => 'date',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'actual_completion_date' => 'date',
        'last_notified_at' => 'datetime',
        'units_required' => 'integer',
        'units_earned_prior' => 'integer',
        // Encrypted sensitive fields
        'contact_number' => EncryptedAttribute::class,
    ];

    /**
     * Validation rules for scholar profile fields.
     *
     * @var array<string, string>
     */
    public static $validationRules = [
        // Personal Information (Required)
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'suffix' => 'nullable|string|max:10',
        'birth_date' => 'required|date|date_format:Y-m-d',
        'gender' => 'required|in:Male,Female,Other,Prefer not to say',
        'contact_number' => 'required|string|max:20',
        // Home Address Structure
        'street' => 'nullable|string|max:255',
        'village' => 'nullable|string|max:255',
        'town' => 'nullable|string|max:255',
        'province' => 'nullable|string|max:255',
        'zipcode' => 'nullable|string|max:10',
        'district' => 'nullable|string|max:20',
        'region' => 'nullable|string|max:20',
        // Educational Background
        'course_completed' => 'nullable|string|max:255',
        'university_graduated' => 'nullable|string|max:255',
        // Application Details
        'entry_type' => 'nullable|in:NEW,LATERAL',
        'intended_degree' => 'nullable|string|max:255',

        'intended_university' => 'nullable|string|max:255',
        'department' => 'nullable|string|max:255',
        'thesis_dissertation_title' => 'nullable|string',
        // Academic Load (for Lateral Entrants)
        'units_required' => 'nullable|integer|min:0',
        'units_earned_prior' => 'nullable|integer|min:0',
        // Scholarship Details
        'start_date' => 'nullable|date|date_format:Y-m-d',

        'study_time' => 'required|string|in:Full-time,Part-time',
        'scholarship_duration' => 'required|integer|min:1|max:60',
        'status' => 'required|string|in:Active,Graduated,Deferred,Dropped,Inactive',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Add global scope for role-based access control
        static::addGlobalScope(new ScholarAccessScope);

        static::creating(function ($model) {
            // Log profile creation
            Log::info('Scholar profile being created', [
                'user_id' => Auth::id(),
                'timestamp' => now(),
            ]);
        });

        static::created(function ($model) {
            // Log after creation when ID is available
            Log::info('Scholar profile created', [
                'user_id' => Auth::id(),
                'scholar_profile_id' => $model->getKey(),
                'timestamp' => now(),
            ]);
        });

        static::updating(function ($model) {
            // Log access to sensitive fields
            $sensitiveFields = ['contact_number', 'phone'];
            foreach ($sensitiveFields as $field) {
                if ($model->isDirty($field)) {
                    Log::info('Sensitive data updated', [
                        'user_id' => Auth::id(),
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
     * Scope to eager load all common relationships
     */
    public function scopeWithFullRelations($query)
    {
        return $query->with([
            'user',
            'fundRequests.requestType',
            'fundRequests.documents',
            'manuscripts.documents',
            'documents'
        ]);
    }

    /**
     * Scope to eager load basic relationships
     */
    public function scopeWithBasicRelations($query)
    {
        return $query->with(['user']);
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
     * Get the stipend disbursements for the scholar.
     */
    public function stipendDisbursements()
    {
        return $this->hasMany(StipendDisbursement::class);
    }

    /**
     * Get the latest stipend disbursement for the scholar.
     */
    public function latestStipendDisbursement()
    {
        return $this->hasOne(StipendDisbursement::class)->latest();
    }

    /**
     * Get the full name of the scholar.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        $name = trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
        return $this->suffix ? $name . ' ' . $this->suffix : $name;
    }

    /**
     * Get the formatted full address.
     *
     * @return string
     */
    public function getFormattedAddressAttribute()
    {
        $addressParts = array_filter([
            $this->street,
            $this->village,
            $this->town,
            $this->district,
            $this->region,
            $this->province,
            $this->zipcode,
            $this->country
        ]);
        
        return implode(', ', $addressParts);
    }

    /**
     * Get the formatted gender display.
     *
     * @return string
     */
    public function getGenderDisplayAttribute()
    {
        return $this->gender === 'F' ? 'Female' : ($this->gender === 'M' ? 'Male' : '');
    }

    /**
     * Get the formatted entry type display.
     *
     * @return string
     */
    public function getEntryTypeDisplayAttribute()
    {
        return $this->entry_type === 'NEW' ? 'New Applicant' : ($this->entry_type === 'LATERAL' ? 'Lateral Entry' : '');
    }


    /**
     * Get the admin who verified the scholar profile.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }





    /**
     * Scope to filter by status (maps to scholar_status)
     */
    public function scopeWhereStatus($query, $status)
    {
        $statusMap = [
            'Active' => 'active',
            'Graduated' => 'graduated',
            'Deferred' => 'deferred',
            'Dropped' => 'dropped',
            'Inactive' => 'inactive',
            // Legacy mappings for backward compatibility
            'Ongoing' => 'active',
            'Completed' => 'graduated',
            'Discontinued' => 'dropped',
            'On Leave' => 'deferred',
            'Suspended' => 'inactive',
            'Pending' => 'active'
        ];
        
        $mappedStatus = $statusMap[$status] ?? strtolower($status);
        return $query->where('scholar_status', $mappedStatus);
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
     * Get the scholar status attribute.
     */
    public function getScholarStatusAttribute($value)
    {
        return $value;
    }

    /**
     * Set the scholar status attribute.
     */
    public function setScholarStatusAttribute($value)
    {
        $this->attributes['scholar_status'] = $value;
    }

    /**
     * Get the status attribute (alias for scholar_status).
     */
    public function getStatusAttribute()
    {
        return $this->scholar_status;
    }

    /**
     * Set the status attribute (alias for scholar_status).
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['scholar_status'] = $value;
    }

    /**
     * Log sensitive data access
     */
    public function logSensitiveDataAccess($field)
    {
        Log::info('Sensitive data accessed', [
            'user_id' => Auth::id(),
            'scholar_profile_id' => $this->getKey(),
            'field' => $field,
            'timestamp' => now(),
        ]);
    }
}
