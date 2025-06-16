<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryContentBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'key',
        'value',
        'type',
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

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // Static helper methods
    public static function getContent($section, $key, $default = '')
    {
        $block = static::active()
            ->where('section', $section)
            ->where('key', $key)
            ->first();

        return $block ? $block->value : $default;
    }

    public static function getSectionContent($section)
    {
        return static::active()
            ->bySection($section)
            ->ordered()
            ->pluck('value', 'key')
            ->toArray();
    }
}
