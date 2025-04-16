<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'manuscript_id',
        'user_id',
        'comment',
    ];

    /**
     * Get the manuscript that owns the review comment.
     */
    public function manuscript()
    {
        return $this->belongsTo(Manuscript::class);
    }

    /**
     * Get the user that owns the review comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}