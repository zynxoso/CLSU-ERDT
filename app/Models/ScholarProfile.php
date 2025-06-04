<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

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
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'university',
        'department',
        'program',
        'degree_program',
        'year_level',
        'expected_graduation',
        'status',
        'scholar_id',
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
        'notes',
        'degree_level',
        'enrollment_type',
        'study_time',
        'scholarship_duration',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'expected_graduation' => 'date',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'start_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'date_of_birth' => 'encrypted',
        'contact_number' => 'encrypted',
        'emergency_contact' => 'encrypted',
    ];

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
}
