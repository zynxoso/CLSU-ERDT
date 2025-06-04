<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Document extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scholar_profile_id',
        'fund_request_id',
        'manuscript_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'category',
        'is_verified',
        'verified_by',
        'verified_at',
        'description',
        'status',
        'security_scanned',
        'security_scanned_at',
        'security_scan_result',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'security_scanned' => 'boolean',
        'security_scanned_at' => 'datetime',
    ];

    /**
     * Get the scholar profile that owns the document.
     */
    public function scholarProfile()
    {
        return $this->belongsTo(ScholarProfile::class);
    }

    /**
     * Get the fund request that owns the document.
     */
    public function fundRequest()
    {
        return $this->belongsTo(FundRequest::class);
    }

    /**
     * Get the manuscript that owns the document.
     */
    public function manuscript()
    {
        return $this->belongsTo(Manuscript::class);
    }

    /**
     * Get the user who verified the document.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
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
            'scholar_profile_id', // Local key on documents table
            'user_id' // Local key on scholar_profiles table
        );
    }
}
