<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'is_active',
        'priority',
        'published_at',
        'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrderByPriority($query)
    {
        return $query->orderBy('priority', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    // Get badge color based on type
    public function getBadgeColorAttribute()
    {
        return match($this->type) {
            'urgent' => 'bg-red-100 text-red-800 border-red-500',
            'application' => 'bg-blue-100 text-blue-800 border-blue-500',
            'scholarship' => 'bg-green-100 text-green-800 border-green-500',
            'event' => 'bg-purple-100 text-purple-800 border-purple-500',
            'general' => 'bg-yellow-100 text-yellow-800 border-yellow-500',
            default => 'bg-gray-100 text-gray-800 border-gray-500'
        };
    }

    // Get badge label
    public function getBadgeLabelAttribute()
    {
        return match($this->type) {
            'urgent' => 'URGENT',
            'application' => 'APPLICATION',
            'scholarship' => 'SCHOLARSHIP',
            'event' => 'EVENT',
            'general' => 'GENERAL',
            default => 'INFO'
        };
    }
}
