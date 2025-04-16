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
        'description',
        'status',
        'admin_notes',
        'verified_by',
        'verified_at',
        'version',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
        'verified_at' => 'datetime',
        'version' => 'integer',
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
     * Get the admin who verified the document.
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

    /**
     * Get the formatted file size.
     *
     * @return string
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
