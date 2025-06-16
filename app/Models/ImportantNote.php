<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantNote extends Model
{
    use HasFactory;

    protected $table = 'important_notes';

    protected $fillable = [
        'title',
        'content',
        'type',
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

    // Scope by type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
