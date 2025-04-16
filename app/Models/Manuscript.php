<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Manuscript extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scholar_profile_id',
        'reference_number',
        'title',
        'abstract',
        'manuscript_type',
        'co_authors',
        'keywords',
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
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the scholar profile that owns the manuscript.
     */
    public function scholarProfile()
    {
        return $this->belongsTo(ScholarProfile::class);
    }

    /**
     * Get the documents for the manuscript.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the review comments for the manuscript.
     */
    public function reviewComments()
    {
        return $this->hasMany(ReviewComment::class);
    }

    /**
     * Get the admin who reviewed the manuscript.
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
            'scholar_profile_id', // Local key on manuscripts table
            'user_id' // Local key on scholar_profiles table
        );
    }
}
