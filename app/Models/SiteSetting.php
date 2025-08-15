<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Cache duration for settings (in minutes)
     */
    const CACHE_DURATION = 60;

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "site_setting_{$key}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general', string $description = null): void
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => self::prepareValue($value, $type),
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );

        // Clear cache
        Cache::forget("site_setting_{$key}");
        Cache::forget('site_settings_all');
    }

    /**
     * Get all settings grouped by group
     */
    public static function getAllGrouped(): array
    {
        return Cache::remember('site_settings_all', self::CACHE_DURATION, function () {
            return self::all()->groupBy('group')->map(function ($settings) {
                return $settings->mapWithKeys(function ($setting) {
                    return [$setting->key => self::castValue($setting->value, $setting->type)];
                });
            })->toArray();
        });
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group): array
    {
        $cacheKey = "site_settings_group_{$group}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($group) {
            return self::where('group', $group)->get()->mapWithKeys(function ($setting) {
                return [$setting->key => self::castValue($setting->value, $setting->type)];
            })->toArray();
        });
    }

    /**
     * Cast value to appropriate type
     */
    protected static function castValue($value, string $type)
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            'date' => Carbon::parse($value),
            'array' => is_string($value) ? explode(',', $value) : $value,
            default => (string) $value,
        };
    }

    /**
     * Prepare value for storage
     */
    protected static function prepareValue($value, string $type): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            'date' => $value instanceof Carbon ? $value->toDateTimeString() : $value,
            'array' => is_array($value) ? implode(',', $value) : $value,
            default => (string) $value,
        };
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('site_settings_all');

        // Clear individual setting caches
        self::all()->each(function ($setting) {
            Cache::forget("site_setting_{$setting->key}");
        });

        // Clear group caches
        $groups = self::distinct('group')->pluck('group');
        $groups->each(function ($group) {
            Cache::forget("site_settings_group_{$group}");
        });
    }

    /**
     * Boot method to clear cache when settings are updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    /**
     * Get current semester based on configuration
     */
    public static function getCurrentSemester(): string
    {
        $now = Carbon::now();
        $firstSemStart = self::get('first_semester_start');
        $firstSemEnd = self::get('first_semester_end');
        $secondSemStart = self::get('second_semester_start');
        $secondSemEnd = self::get('second_semester_end');
        $summerStart = self::get('summer_term_start');
        $summerEnd = self::get('summer_term_end');

        if ($firstSemStart && $firstSemEnd) {
            $firstStart = Carbon::parse($firstSemStart);
            $firstEnd = Carbon::parse($firstSemEnd);

            if ($now->between($firstStart, $firstEnd)) {
                return 'first';
            }
        }

        if ($secondSemStart && $secondSemEnd) {
            $secondStart = Carbon::parse($secondSemStart);
            $secondEnd = Carbon::parse($secondSemEnd);

            if ($now->between($secondStart, $secondEnd)) {
                return 'second';
            }
        }

        if ($summerStart && $summerEnd) {
            $summerStartDate = Carbon::parse($summerStart);
            $summerEndDate = Carbon::parse($summerEnd);

            if ($now->between($summerStartDate, $summerEndDate)) {
                return 'summer';
            }
        }

        return 'none'; // Outside semester periods
    }

    /**
     * Check if applications are currently open
     */
    public static function areApplicationsOpen(): bool
    {
        $currentSemester = self::getCurrentSemester();

        if ($currentSemester === 'none') {
            return false;
        }

        $deadlineKey = $currentSemester === 'first' ? 'application_deadline_1st' : 'application_deadline_2nd';
        $deadline = self::get($deadlineKey);

        if (!$deadline) {
            return false;
        }

        return Carbon::now()->lte(Carbon::parse($deadline));
    }
}
