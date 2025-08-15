<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    /**
     * Document requirements configuration
     */
    public const DOCUMENT_REQUIREMENTS = [
        'Tuition and other school fees' => [
            'documents' => ['Registration Form/Enrollment Form'],
            'frequency' => 'semester',
            'guidance' => 'Upload your official registration or enrollment form from your institution.',
            'file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
            'max_size' => 5120, // 5MB in KB
        ],
        'Learning Materials and/or Connectivity Allowance' => [
            'documents' => ['Official Receipt', 'List of Materials'],
            'frequency' => 'semester',
            'guidance' => 'Provide official receipts and a detailed list of learning materials purchased.',
            'file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
            'max_size' => 5120,
        ],
        'Transportation Allowance' => [
            'documents' => ['Transportation Receipts', 'Travel Itinerary'],
            'frequency' => 'monthly',
            'guidance' => 'Submit transportation receipts and travel itinerary for academic purposes.',
            'file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
            'max_size' => 5120,
        ],
        'Research Dissemination Grant' => [
            'documents' => ['Conference Registration', 'Abstract/Paper', 'Travel Documents'],
            'frequency' => 'per_event',
            'guidance' => 'Provide conference registration, accepted abstract/paper, and travel documentation.',
            'file_types' => ['pdf', 'doc', 'docx'],
            'max_size' => 10240, // 10MB
        ],
        'Mentor\'s Fee' => [
            'documents' => ['Mentorship Agreement', 'Progress Report'],
            'frequency' => 'semester',
            'guidance' => 'Submit signed mentorship agreement and progress reports.',
            'file_types' => ['pdf', 'doc', 'docx'],
            'max_size' => 5120,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'required_documents',
        'document_frequency',
        'document_guidance',
        'max_amount_masters',
        'max_amount_doctoral',
        'frequency',
        'is_requestable',
        'special_requirements',
        'max_amount',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'required_documents' => 'array',
        'special_requirements' => 'array',
        'max_amount_masters' => 'decimal:2',
        'max_amount_doctoral' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_requestable' => 'boolean',
    ];

    /**
     * Get the fund requests for the request type.
     */
    public function fundRequests()
    {
        return $this->hasMany(FundRequest::class);
    }

    /**
     * Get document requirements for this request type.
     */
    public function getDocumentRequirements(): array
    {
        return self::DOCUMENT_REQUIREMENTS[$this->name] ?? [];
    }

    /**
     * Get maximum amount for a specific degree level.
     */
    public function getMaxAmountForDegree(string $intendedDegree): ?float
    {
        $degree = strtolower($intendedDegree);
        
        if (str_contains($degree, 'master')) {
            return $this->max_amount_masters;
        }
        
        if (str_contains($degree, 'phd') || str_contains($degree, 'doctorate') || str_contains($degree, 'doctoral')) {
            return $this->max_amount_doctoral;
        }
        
        return $this->max_amount;
    }

    /**
     * Check if this request type is available for scholars to request.
     */
    public function isRequestableByScholars(): bool
    {
        return $this->is_active && $this->is_requestable;
    }

    /**
     * Validate document against requirements.
     */
    public function validateDocument(string $fileName, string $mimeType, int $fileSize): array
    {
        $requirements = $this->getDocumentRequirements();
        $errors = [];
        
        if (empty($requirements)) {
            return $errors;
        }
        
        // Check file type
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($extension, $requirements['file_types'] ?? [])) {
            $errors[] = 'Invalid file type. Allowed types: ' . implode(', ', $requirements['file_types'] ?? []);
        }
        
        // Check file size (convert bytes to KB)
        $fileSizeKB = $fileSize / 1024;
        if ($fileSizeKB > ($requirements['max_size'] ?? 5120)) {
            $maxSizeMB = ($requirements['max_size'] ?? 5120) / 1024;
            $errors[] = "File size exceeds maximum allowed size of {$maxSizeMB}MB";
        }
        
        return $errors;
    }

    /**
     * Get requestable types for scholars (excludes stipends).
     */
    public static function getRequestableTypes()
    {
        return static::where('is_active', true)
                    ->where('is_requestable', true)
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Get all active types (for admin use).
     */
    public static function getActiveTypes()
    {
        return static::where('is_active', true)
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Check if request type has special requirements for specific degrees.
     */
    public function hasSpecialRequirements(): bool
    {
        return !empty($this->special_requirements);
    }

    /**
     * Get special requirements for a degree.
     */
    public function getSpecialRequirementsForDegree(string $intendedDegree): array
    {
        $requirements = $this->special_requirements ?? [];
        return $requirements[$intendedDegree] ?? [];
    }
}
