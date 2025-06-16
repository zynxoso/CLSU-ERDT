<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationTimeline extends Model
{
    use HasFactory;

    protected $table = 'application_timeline';

    protected $fillable = [
        'activity',
        'first_semester',
        'second_semester',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope for active items
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered items
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
