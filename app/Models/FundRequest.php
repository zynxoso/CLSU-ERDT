<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class FundRequest extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scholar_profile_id',
        'request_type_id',
        'reference_number',
        'amount',
        'purpose',
        'details',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the scholar profile that owns the fund request.
     */
    public function scholarProfile()
    {
        return $this->belongsTo(ScholarProfile::class);
    }

    /**
     * Get the request type that owns the fund request.
     */
    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }

    /**
     * Get the documents for the fund request.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the disbursements for the fund request.
     */
    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }

    /**
     * Get the admin who reviewed the fund request.
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the user through the scholar profile.
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            ScholarProfile::class,
            'id', // Foreign key on scholar_profiles table
            'id', // Foreign key on users table
            'scholar_profile_id', // Local key on fund_requests table
            'user_id' // Local key on scholar_profiles table
        );
    }
}
