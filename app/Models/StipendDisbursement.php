<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class StipendDisbursement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scholar_profile_id',
        'reference_number',
        'amount',
        'disbursement_type',
        'disbursement_date',
        'period_covered',
        'description',
        'status',
        'calculation_details',
        'rejection_reason',
        'processed_by',
        'processed_at',
        'notification_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'disbursement_date' => 'date',
        'processed_at' => 'datetime',
        'calculation_details' => 'array',
        'notification_status' => 'array',
    ];

    /**
     * Get the scholar profile that owns the stipend disbursement.
     */
    public function scholarProfile(): BelongsTo
    {
        return $this->belongsTo(ScholarProfile::class);
    }

    /**
     * Get the user who processed the disbursement.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope to get disbursements for a specific period.
     */
    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('disbursement_date', [$startDate, $endDate]);
    }

    /**
     * Scope to get disbursements by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get disbursements by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('disbursement_type', $type);
    }

    /**
     * Generate a unique reference number for the disbursement.
     */
    public static function generateReferenceNumber(): string
    {
        $prefix = 'STD';
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        
        // Get the next sequence number for this month
        $lastDisbursement = static::where('reference_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->orderBy('reference_number', 'desc')
            ->first();
        
        if ($lastDisbursement) {
            $lastSequence = (int) substr($lastDisbursement->reference_number, -4);
            $sequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }
        
        return "{$prefix}-{$year}{$month}-{$sequence}";
    }

    /**
     * Mark the disbursement as processed.
     */
    public function markAsProcessed(User $processedBy, ?string $notes = null): void
    {
        $this->update([
            'status' => 'Processed',
            'processed_by' => $processedBy->id,
            'processed_at' => Carbon::now(),
            'rejection_reason' => $notes,
        ]);
    }

    /**
     * Mark the disbursement as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => 'Completed']);
    }

    /**
     * Mark the disbursement as failed.
     */
    public function markAsFailed(?string $reason = null): void
    {
        $this->update([
            'status' => 'Failed',
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Update notification status.
     */
    public function updateNotificationStatus(array $status): void
    {
        $currentStatus = $this->notification_status ?? [];
        $this->update([
            'notification_status' => array_merge($currentStatus, $status)
        ]);
    }

    /**
     * Check if notification was sent successfully.
     */
    public function isNotificationSent(): bool
    {
        $status = $this->notification_status ?? [];
        return isset($status['email_sent']) && $status['email_sent'] === true;
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₱' . number_format($this->amount, 2);
    }

    /**
     * Get formatted disbursement date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->disbursement_date->format('F j, Y');
    }
}