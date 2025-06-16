<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTimelineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'year_label',
        'category',
        'icon',
        'color',
        'sort_order',
        'is_active',
        'image_path'
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('event_date', 'asc');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('Y');
    }

    public function getDisplayYearAttribute()
    {
        return $this->year_label ?: $this->formatted_date;
    }
}
