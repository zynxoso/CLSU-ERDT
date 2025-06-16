<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'department',
        'specialization',
        'education_background',
        'research_description',
        'photo_path',
        'expertise_tags',
        'degree_level',
        'university_origin',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'expertise_tags' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path && file_exists(storage_path('app/public/' . $this->photo_path))) {
            return asset('storage/' . $this->photo_path);
        }
        return null;
    }
}
