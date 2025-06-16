<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'statistic',
        'statistic_label',
        'color',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // Accessors
    public function getFormattedStatisticAttribute()
    {
        if ($this->statistic && $this->statistic_label) {
            return $this->statistic . ' ' . $this->statistic_label;
        }
        return $this->statistic;
    }
}
