<?php

namespace App\Services;

use App\Models\StipendDisbursement;
use App\Models\ScholarProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class StipendService
{
    /**
     * Monthly stipend amounts by program level
     */
    private const STIPEND_AMOUNTS = [
        'masters' => 30000.00,
        'doctoral' => 38000.00,
    ];

    /**
     * Process monthly stipends for all active scholars.
     */
    public function processMonthlyStipends(Carbon $disbursementDate, User $processedBy): array
    {
        $results = [
            'processed' => 0,
            'failed' => 0,
            'errors' => [],
            'disbursements' => []
        ];

        try {
            DB::beginTransaction();

            $activeScholars = $this->getActiveScholars();
            $period = $disbursementDate->format('F Y');

            foreach ($activeScholars as $scholar) {
                try {
                    // Check if stipend already processed for this period
                    if ($this->isStipendAlreadyProcessed($scholar, $period)) {
                        continue;
                    }

                    $amount = $this->calculateStipendAmount($scholar);
                    if ($amount > 0) {
                        $disbursement = $this->createStipendDisbursement(
                            $scholar,
                            $amount,
                            $disbursementDate,
                            $period,
                            'monthly',
                            "Monthly stipend for {$period}",
                            $processedBy
                        );

                        $results['disbursements'][] = $disbursement;
                        $results['processed']++;
                    }
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Failed to process stipend for scholar {$scholar->id}: {$e->getMessage()}";
                    Log::error("Stipend processing error for scholar {$scholar->id}: {$e->getMessage()}");
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Monthly stipend processing failed: {$e->getMessage()}");
            throw $e;
        }

        return $results;
    }

    /**
     * Calculate stipend amount for a scholar.
     */
    public function calculateStipendAmount(ScholarProfile $scholar): float
    {
        $programLevel = $this->determineProgramLevel($scholar->intended_degree);
        $baseAmount = self::STIPEND_AMOUNTS[$programLevel] ?? 0;

        // Apply any adjustments based on scholar status or special conditions
        $adjustments = $this->calculateAdjustments($scholar);
        
        return max(0, $baseAmount + $adjustments);
    }

    /**
     * Create a stipend disbursement record.
     */
    public function createStipendDisbursement(
        ScholarProfile $scholar,
        float $amount,
        Carbon $disbursementDate,
        string $period,
        string $type = 'monthly',
        ?string $description = null,
        ?User $processedBy = null
    ): StipendDisbursement {
        $calculationDetails = [
            'base_amount' => $this->getBaseStipendAmount($scholar),
            'adjustments' => $this->calculateAdjustments($scholar),
            'final_amount' => $amount,
            'intended_degree' => $scholar->intended_degree,
            'calculated_at' => Carbon::now()->toISOString(),
        ];

        return StipendDisbursement::create([
            'scholar_profile_id' => $scholar->id,
            'reference_number' => StipendDisbursement::generateReferenceNumber(),
            'amount' => $amount,
            'disbursement_type' => $type,
            'disbursement_date' => $disbursementDate,
            'period_covered' => $period,
            'description' => $description ?? "Stipend disbursement for {$period}",
            'status' => 'Pending',
            'calculation_details' => $calculationDetails,
            'processed_by' => $processedBy?->id,
            'processed_at' => $processedBy ? Carbon::now() : null,
        ]);
    }

    /**
     * Process stipend for a specific scholar.
     */
    public function processScholarStipend(
        ScholarProfile $scholar,
        float $amount,
        Carbon $disbursementDate,
        string $period,
        string $type = 'manual',
        ?string $description = null,
        User $processedBy
    ): StipendDisbursement {
        // Validate amount
        $maxAmount = $this->getMaxStipendAmount($scholar);
        if ($amount > $maxAmount) {
            throw new \InvalidArgumentException("Amount exceeds maximum stipend limit of ₱{$maxAmount}");
        }

        return $this->createStipendDisbursement(
            $scholar,
            $amount,
            $disbursementDate,
            $period,
            $type,
            $description,
            $processedBy
        );
    }

    /**
     * Get stipend history for a scholar.
     */
    public function getScholarStipendHistory(ScholarProfile $scholar, ?int $limit = null): Collection
    {
        $query = StipendDisbursement::where('scholar_profile_id', $scholar->id)
            ->with(['processedBy'])
            ->orderBy('disbursement_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get stipend summary for a scholar.
     */
    public function getScholarStipendSummary(ScholarProfile $scholar, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = StipendDisbursement::where('scholar_profile_id', $scholar->id);

        if ($startDate) {
            $query->where('disbursement_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('disbursement_date', '<=', $endDate);
        }

        $disbursements = $query->get();

        return [
            'total_amount' => $disbursements->sum('amount'),
            'total_disbursements' => $disbursements->count(),
            'pending_amount' => $disbursements->where('status', 'Pending')->sum('amount'),
            'completed_amount' => $disbursements->where('status', 'Completed')->sum('amount'),
            'last_disbursement' => $disbursements->sortByDesc('disbursement_date')->first(),
            'monthly_average' => $disbursements->where('disbursement_type', 'monthly')->avg('amount') ?? 0,
        ];
    }

    /**
     * Get all scholars eligible for stipends.
     */
    private function getActiveScholars(): Collection
    {
        return ScholarProfile::whereHas('user', function ($query) {
            $query->where('role', 'scholar');
        })
        ->whereStatus('Ongoing')
        ->get();
    }

    /**
     * Check if stipend already processed for a scholar in a given period.
     */
    private function isStipendAlreadyProcessed(ScholarProfile $scholar, string $period): bool
    {
        return StipendDisbursement::where('scholar_profile_id', $scholar->id)
            ->where('period_covered', $period)
            ->where('disbursement_type', 'monthly')
            ->exists();
    }

    /**
     * Determine program level from intended degree.
     */
    private function determineProgramLevel(string $intendedDegree): string
    {
        $degree = strtolower($intendedDegree);
        
        if (str_contains($degree, 'phd') || str_contains($degree, 'doctorate') || str_contains($degree, 'doctoral')) {
            return 'doctoral';
        }
        
        return 'masters';
    }

    /**
     * Get base stipend amount for a scholar.
     */
    private function getBaseStipendAmount(ScholarProfile $scholar): float
    {
        $programLevel = $this->determineProgramLevel($scholar->intended_degree);
        return self::STIPEND_AMOUNTS[$programLevel] ?? 0;
    }

    /**
     * Calculate adjustments to base stipend amount.
     */
    private function calculateAdjustments(ScholarProfile $scholar): float
    {
        $adjustments = 0;

        // Add logic for adjustments based on:
        // - Scholar performance
        // - Special circumstances
        // - Deductions
        // - Bonuses
        
        // For now, return 0 (no adjustments)
        return $adjustments;
    }

    /**
     * Get maximum stipend amount for a scholar.
     */
    private function getMaxStipendAmount(ScholarProfile $scholar): float
    {
        $programLevel = $this->determineProgramLevel($scholar->intended_degree);
        return self::STIPEND_AMOUNTS[$programLevel] ?? 0;
    }



    /**
     * Bulk update disbursement status.
     */
    public function bulkUpdateStatus(array $disbursementIds, string $status, User $updatedBy, ?string $notes = null): int
    {
        $updated = 0;
        
        foreach ($disbursementIds as $id) {
            $disbursement = StipendDisbursement::find($id);
            if ($disbursement) {
                $disbursement->update([
                    'status' => $status,
                    'rejection_reason' => $notes,
                    'processed_by' => $updatedBy->id,
                    'processed_at' => Carbon::now(),
                ]);
                $updated++;
            }
        }
        
        return $updated;
    }
}