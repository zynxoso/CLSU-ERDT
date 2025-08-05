<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class DownloadableForm extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'title',
        'description',
        'filename',
        'file_path',
        'file_size',
        'mime_type',
        'category',
        'status',
        'download_count',
        'uploaded_by',
        'sort_order'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'sort_order' => 'integer',
        'status' => 'boolean'
    ];

    protected $attributes = [
        'status' => true,
        'download_count' => 0,
        'sort_order' => 0
    ];

    /**
     * Get the user who uploaded this form
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope for active forms
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for forms by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for ordered forms
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Check if file exists
     */
    public function fileExists()
    {
        return file_exists(public_path($this->file_path));
    }

    /**
     * Get available categories
     */
    public static function getCategories()
    {
        return [
            'application' => 'Application Forms',
            'scholarship' => 'Scholarship Forms',
            'research' => 'Research Forms',
            'administrative' => 'Administrative Forms',
            'academic' => 'Academic Forms',
            'other' => 'Other Forms'
        ];
    }
}