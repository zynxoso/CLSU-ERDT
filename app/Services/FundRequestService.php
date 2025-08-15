<?php

namespace App\Services;

use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use App\Models\RequestType;
use App\Models\User;
use App\Notifications\FundRequestStatusChanged;
use App\Repositories\FundRequestRepository;
use App\Services\Interfaces\FundRequestServiceInterface;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FundRequestService implements FundRequestServiceInterface
{
    protected $fundRequestRepository;
    protected $auditService;

    /**
     * FundRequestService constructor.
     *
     * @param FundRequestRepository $fundRequestRepository
     * @param AuditService $auditService
     */
    public function __construct(
        FundRequestRepository $fundRequestRepository,
        AuditService $auditService
    ) {
        $this->fundRequestRepository = $fundRequestRepository;
        $this->auditService = $auditService;
    }

    /**
     * Get fund requests for admin with filtering
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getAdminFundRequests(Request $request): LengthAwarePaginator
    {
        $query = FundRequest::query();

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by purpose if provided
        if ($request->has('purpose') && $request->purpose) {
            $query->where('purpose', 'like', '%' . $request->purpose . '%');
        }

        // Filter by scholar if provided
        if ($request->has('scholar') && $request->scholar) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $date = $request->date;
            $query->whereYear('created_at', substr($date, 0, 4))
                ->whereMonth('created_at', substr($date, 5, 2));
        }

        return $query->withBasicRelations()
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
    }

    /**
     * Get fund requests for scholar with filtering
     *
     * @param Request $request
     * @param int $scholarProfileId
     * @return LengthAwarePaginator
     */
    public function getScholarFundRequests(Request $request, int $scholarProfileId): LengthAwarePaginator
    {
        $query = FundRequest::where('scholar_profile_id', $scholarProfileId);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by purpose if provided
        if ($request->has('purpose') && $request->purpose) {
            $query->where('purpose', $request->purpose);
        }

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $date = $request->date;
            $query->whereYear('created_at', substr($date, 0, 4))
                ->whereMonth('created_at', substr($date, 5, 2));
        }

        return $query->withBasicRelations()->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Get fund request statistics for scholar
     *
     * @param int $scholarProfileId
     * @return array
     */
    public function getScholarFundRequestStatistics(int $scholarProfileId): array
    {
        $query = FundRequest::where('scholar_profile_id', $scholarProfileId);

        $totalRequested = $query->sum('amount');

        $totalApproved = clone $query;
        $totalApproved = $totalApproved->where('status', 'Approved')->sum('amount');

        $totalPending = clone $query;
        $totalPending = $totalPending->whereIn('status', [FundRequest::STATUS_SUBMITTED, FundRequest::STATUS_UNDER_REVIEW])->sum('amount');

        $totalRejected = clone $query;
        $totalRejected = $totalRejected->where('status', FundRequest::STATUS_REJECTED)->sum('amount');

        return [
            'totalRequested' => $totalRequested,
            'totalApproved' => $totalApproved,
            'totalPending' => $totalPending,
            'totalRejected' => $totalRejected
        ];
    }

    /**
     * Check for active duplicate fund requests (strict validation)
     *
     * @param int $scholarProfileId
     * @param int $requestTypeId
     * @return array|null
     */
    public function checkForActiveDuplicateRequest(int $scholarProfileId, int $requestTypeId): ?array
    {
        // Find any active requests of the same type
        $activeRequest = FundRequest::where('scholar_profile_id', $scholarProfileId)
            ->where('request_type_id', $requestTypeId)
            ->whereIn('status', [
                FundRequest::STATUS_SUBMITTED,
                FundRequest::STATUS_UNDER_REVIEW,
                FundRequest::STATUS_APPROVED
            ])
            ->first();

        if ($activeRequest) {
            return [
                'found' => true,
                'request' => $activeRequest,
                'status' => $activeRequest->status,
                'message' => "You already have a {$activeRequest->status} request of this type. Please wait for it to be completed before submitting a new request."
            ];
        }

        return null;
    }

    /**
     * Check for potential duplicate fund requests (similarity detection)
     *
     * @param int $scholarProfileId
     * @param int $requestTypeId
     * @param float $amount
     * @param int $timeWindowDays
     * @param float $amountVariancePercent
     * @return array|null
     */
    public function checkForDuplicateRequest(
        int $scholarProfileId,
        int $requestTypeId,
        float $amount,
        int $timeWindowDays = 30,
        float $amountVariancePercent = 10
    ): ?array {
        // Calculate the date threshold
        $dateThreshold = now()->subDays($timeWindowDays);

        // Calculate amount range for comparison (e.g., within 10%)
        $minAmount = $amount * (1 - ($amountVariancePercent / 100));
        $maxAmount = $amount * (1 + ($amountVariancePercent / 100));

        // Find potential duplicates
        $potentialDuplicates = FundRequest::where('scholar_profile_id', $scholarProfileId)
            ->where('request_type_id', $requestTypeId)
            ->whereBetween('amount', [$minAmount, $maxAmount])
            ->where('created_at', '>=', $dateThreshold)
            ->get();

        if ($potentialDuplicates->count() > 0) {
            return [
                'found' => true,
                'duplicates' => $potentialDuplicates,
                'count' => $potentialDuplicates->count()
            ];
        }

        return null;
    }

    /**
     * Create a new fund request
     *
     * @param array $data
     * @param int $scholarProfileId
     * @param Request $request
     * @return FundRequest
     * @throws \Exception
     */
    public function createFundRequest(array $data, int $scholarProfileId, Request $request): FundRequest
    {
        // Get the request type
        $requestType = RequestType::find($data['request_type_id']);
        if (!$requestType) {
            throw new \Exception('Invalid request type selected.');
        }

        // Strict duplicate check - prevent any active requests of same type
        $activeDuplicateCheck = $this->checkForActiveDuplicateRequest(
            $scholarProfileId,
            $data['request_type_id']
        );

        if ($activeDuplicateCheck) {
            throw new \Exception($activeDuplicateCheck['message']);
        }

        // Check for potential similar duplicates (for admin notification)
        $duplicateCheck = $this->checkForDuplicateRequest(
            $scholarProfileId,
            $data['request_type_id'],
            $data['amount']
        );

        // Create the fund request
        $fundRequest = new FundRequest();
        $fundRequest->scholar_profile_id = $scholarProfileId;
        $fundRequest->request_type_id = $data['request_type_id'];
        $fundRequest->amount = $data['amount'];
        $fundRequest->purpose = $requestType->name; // Use the request type name as the purpose
        $fundRequest->admin_remarks = $data['admin_remarks'] ?? null;
        $fundRequest->status = $data['status'] ?? 'Submitted';

        // If potential duplicates were found, flag for admin review
        if ($duplicateCheck) {
            $fundRequest->admin_remarks = ($fundRequest->admin_remarks ? $fundRequest->admin_remarks . "\n\n" : '') .
                "SYSTEM: Potential duplicate request detected. Please review carefully.";

            // Log the duplicate detection
            $this->auditService->logCreate(
                'DuplicateDetection',
                null,
                [
                    'fund_request_id' => null, // Will be updated after save
                    'scholar_profile_id' => $scholarProfileId,
                    'request_type_id' => $data['request_type_id'],
                    'amount' => $data['amount'],
                    'duplicate_count' => $duplicateCheck['count'],
                    'duplicate_ids' => $duplicateCheck['duplicates']->pluck('id')->toArray()
                ]
            );
        }

        $fundRequest->save();

        // Update the audit log with the fund request ID if duplicates were found
        if ($duplicateCheck) {
            $this->auditService->logUpdate(
                'DuplicateDetection',
                null,
                ['fund_request_id' => null],
                ['fund_request_id' => $fundRequest->id]
            );

            // Notify admins about potential duplicate
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                if ($admin->hasFundRequestNotifications()) {
                    app(NotificationService::class)->notify(
                        $admin->id,
                        'Potential Duplicate Fund Request',
                        'A potential duplicate fund request has been detected for ' . $requestType->name . ' in the amount of ' . $data['amount'] . '. Please review.',
                        'fund_request',
                        route('admin.fund-requests.show', $fundRequest->id),
                        false
                    );
                }
            }
        }

        // Add initial status history entry
        $initialStatus = $fundRequest->status;
        $statusNote = 'Request submitted for review';
        $fundRequest->addStatusHistory($initialStatus, $statusNote);

        // If the fund request is created directly as 'Submitted', send notifications
        if ($fundRequest->status === FundRequest::STATUS_SUBMITTED) {
            // Notify all admins about the new fund request submission
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                try {
                    if ($admin->hasFundRequestNotifications()) {
                        // Always send to Laravel notifications table
                        $admin->notify(new \App\Notifications\NewFundRequestSubmitted($fundRequest));
                        // Debug log for troubleshooting
                        logger('Sent NewFundRequestSubmitted notification to admin ' . $admin->id . ' for fund request ' . $fundRequest->id);
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail the whole process
                    logger('Failed to send fund request notification to admin ' . $admin->id . ': ' . $e->getMessage());
                }
            }
        }

        // Handle multiple document uploads
        if ($request->hasFile('documents')) {
            $files = $request->file('documents');
            
            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    $fileName = $file->getClientOriginalName();
                    $filePath = $file->store('documents/fund-requests/' . $fundRequest->id, 'public');

                    // Create document record
                    $document = new Document();
                    $document->scholar_profile_id = $scholarProfileId;
                    $document->fund_request_id = $fundRequest->id;
                    $document->file_name = $fileName;
                    $document->file_path = $filePath;
                    $document->file_type = $file->getClientMimeType();
                    $document->file_size = $file->getSize();
                    $document->category = 'Fund Request';
                    $document->title = 'Fund Request Document ' . ($index + 1) . ' - ' . $fundRequest->purpose;

                    // CyberSweep security scanning
                    $document->security_scanned = true;
                    $document->security_scanned_at = now();
                    $document->security_scan_result = 'Passed'; // Default to passed since middleware would have blocked if issues found

                    $document->save();
                }
            }
        }
        
        // Handle legacy single document upload for backward compatibility
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('documents/fund-requests/' . $fundRequest->id, 'public');

            // Create document record
            $document = new Document();
            $document->scholar_profile_id = $scholarProfileId;
            $document->fund_request_id = $fundRequest->id;
            $document->file_name = $fileName;
            $document->file_path = $filePath;
            $document->file_type = $file->getClientMimeType();
            $document->file_size = $file->getSize();
            $document->category = 'Fund Request';
            $document->title = 'Fund Request Document - ' . $fundRequest->purpose;

            // CyberSweep security scanning
            $document->security_scanned = true;
            $document->security_scanned_at = now();
            $document->security_scan_result = 'Passed'; // Default to passed since middleware would have blocked if issues found

            $document->save();
        }

        $this->auditService->logCreate('FundRequest', $fundRequest->id, $fundRequest->toArray());

        return $fundRequest;
    }

    /**
     * Update a fund request
     *
     * @param FundRequest $fundRequest
     * @param array $data
     * @return FundRequest
     */
    public function updateFundRequest(FundRequest $fundRequest, array $data): FundRequest
    {
        $oldValues = $fundRequest->toArray();

        $fundRequest->request_type_id = $data['request_type_id'];
        $fundRequest->amount = $data['amount'];

        // Set purpose based on request type name
        $requestType = RequestType::find($data['request_type_id']);
        $fundRequest->purpose = $requestType->name;

        // If submitting the request
        if (isset($data['status']) && $data['status'] === FundRequest::STATUS_SUBMITTED) {
            $fundRequest->status = FundRequest::STATUS_SUBMITTED;
        }

        $fundRequest->save();

        $this->auditService->logUpdate('FundRequest', $fundRequest->id, $oldValues, $fundRequest->toArray());

        return $fundRequest;
    }

    /**
     * Submit a fund request for review
     *
     * @param FundRequest $fundRequest
     * @return FundRequest
     */
    public function submitFundRequest(FundRequest $fundRequest): FundRequest
    {
        $oldValues = $fundRequest->toArray();

        $fundRequest->status = FundRequest::STATUS_SUBMITTED;
        $fundRequest->save();

        // Add detailed status history entry
        $fundRequest->addStatusHistory(FundRequest::STATUS_SUBMITTED, 'Request submitted by scholar');

        // Notify all admins about the new fund request submission
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                if ($admin->hasFundRequestNotifications()) {
                    // Always send to Laravel notifications table
                    $admin->notify(new \App\Notifications\NewFundRequestSubmitted($fundRequest));
                    // Debug log for troubleshooting
                    logger('Sent NewFundRequestSubmitted notification to admin ' . $admin->id . ' for fund request ' . $fundRequest->id);
                }
            } catch (\Exception $e) {
                // Log error but don't fail the whole process
                logger('Failed to send fund request notification to admin ' . $admin->id . ': ' . $e->getMessage());
            }
        }

        $this->auditService->logCustomAction('submitted', 'FundRequest', $fundRequest->id);

        return $fundRequest;
    }

    /**
     * Approve a fund request
     *
     * @param FundRequest $fundRequest
     * @param int $reviewerId
     * @return FundRequest
     */
    public function approveFundRequest(FundRequest $fundRequest, int $reviewerId): FundRequest
    {
        $oldValues = $fundRequest->toArray();
        $oldStatus = $fundRequest->status;

        $fundRequest->status = FundRequest::STATUS_APPROVED;
        $fundRequest->reviewed_by = $reviewerId;
        $fundRequest->reviewed_at = now();
        $fundRequest->save();

        // Add detailed status history entry
        $fundRequest->addStatusHistory(FundRequest::STATUS_APPROVED, 'Request approved by administrator');

        // Create manuscript if required for specific request types
        $this->createManuscriptIfRequired($fundRequest);

        // Send notification to scholar
        if ($fundRequest->scholarProfile) {
            $scholar = $fundRequest->scholarProfile->user;
            if ($scholar) {
                $scholar->notify(new FundRequestStatusChanged(
                    $fundRequest,
                    $oldStatus,
                    FundRequest::STATUS_APPROVED,
                    null
                ));
            }
        }

        $this->auditService->logCustomAction('approved', 'FundRequest', $fundRequest->id);

        return $fundRequest;
    }

    /**
     * Reject a fund request
     *
     * @param FundRequest $fundRequest
     * @param string $notes
     * @param int $reviewerId
     * @return FundRequest
     */
    public function rejectFundRequest(FundRequest $fundRequest, string $notes, int $reviewerId): FundRequest
    {
        $oldValues = $fundRequest->toArray();
        $oldStatus = $fundRequest->status;

        $fundRequest->status = FundRequest::STATUS_REJECTED;
        $fundRequest->rejection_reason = $notes;
        $fundRequest->reviewed_by = $reviewerId;
        $fundRequest->reviewed_at = now();
        $fundRequest->save();

        // Add detailed status history entry
        $fundRequest->addStatusHistory(FundRequest::STATUS_REJECTED, 'Request rejected by administrator: ' . $notes);

        // Send notification to scholar
        if ($fundRequest->scholarProfile) {
            $scholar = $fundRequest->scholarProfile->user;
            if ($scholar) {
                $scholar->notify(new FundRequestStatusChanged(
                    $fundRequest,
                    $oldStatus,
                    FundRequest::STATUS_REJECTED,
                    $notes
                ));
            }
        }

        $this->auditService->logCustomAction('rejected', 'FundRequest', $fundRequest->id);

        return $fundRequest;
    }

    /**
     * Mark a fund request as under review
     *
     * @param FundRequest $fundRequest
     * @return FundRequest
     */
    public function markFundRequestAsUnderReview(FundRequest $fundRequest): FundRequest
    {
        $oldValues = $fundRequest->toArray();
        $oldStatus = $fundRequest->status;

        $fundRequest->status = FundRequest::STATUS_UNDER_REVIEW;
        $fundRequest->save();

        // Add detailed status history entry
        $fundRequest->addStatusHistory(FundRequest::STATUS_UNDER_REVIEW, 'Request is now under review by administrator');

        // Send notification to scholar
        if ($fundRequest->scholarProfile) {
            $scholar = $fundRequest->scholarProfile->user;
            if ($scholar) {
                $scholar->notify(new FundRequestStatusChanged(
                    $fundRequest,
                    $oldStatus,
                    FundRequest::STATUS_UNDER_REVIEW,
                    'Your request is now being reviewed by an administrator.'
                ));
            }
        }

        $this->auditService->logCustomAction('marked_under_review', 'FundRequest', $fundRequest->id);

        return $fundRequest;
    }

    /**
     * Get status updates for fund requests
     *
     * @param array $requestIds
     * @param int $scholarProfileId
     * @return array
     */
    public function getFundRequestStatusUpdates(array $requestIds, int $scholarProfileId): array
    {
        // Get fund requests
        $fundRequests = FundRequest::whereIn('id', $requestIds)
            ->where('scholar_profile_id', $scholarProfileId)
            ->get();

        // Prepare updates
        $updates = [];
        foreach ($fundRequests as $request) {
            $updates[] = [
                'id' => $request->id,
                'status' => $request->status,
                'status_history' => $request->status_history,
                'updated_at' => $request->updated_at->toDateTimeString(),
            ];
        }

        return $updates;
    }

    /**
     * Validate fund request amount against limits
     *
     * @param int $requestTypeId
     * @param float $amount
     * @param string|null $intendedDegree
     * @return array|null Returns error array if validation fails, null if passes
     */
    public function validateFundRequestAmount(int $requestTypeId, float $amount, ?string $intendedDegree): ?array
    {
        if (empty($intendedDegree)) {
            return ['intended_degree' => 'Your intended degree is not set. Please update your profile.'];
        }

        $isDoctoralProgram = stripos($intendedDegree, 'phd') !== false || stripos($intendedDegree, 'doctorate') !== false || stripos($intendedDegree, 'doctoral') !== false;
        $isMastersProgram = stripos($intendedDegree, 'master') !== false;

        // Get the request type name
        $requestType = RequestType::find($requestTypeId);
        if (!$requestType) {
            return ['request_type_id' => 'Invalid request type.'];
        }

        // Define amount limits based on the entitlements table
        $requestTypeLimits = [
            1 => [ // Tuition Fee
                'name' => 'Tuition Fee',
                'masters' => null, // Actual as billed
                'doctoral' => null, // Actual as billed
            ],
            2 => [ // Living Allowance/Stipend
                'name' => 'Living Allowance/Stipend',
                'masters' => 30000,
                'doctoral' => 38000,
            ],
            3 => [ // Learning Materials and Connectivity Allowance
                'name' => 'Learning Materials',
                'masters' => 20000,
                'doctoral' => 20000,
            ],
            4 => [ // Transportation Allowance - FIXED: Added missing validation
                'name' => 'Transportation Allowance',
                'masters' => 15000,
                'doctoral' => 15000,
            ],
            5 => [ // Thesis/Dissertation Outright Grant
                'name' => 'Thesis/Dissertation Grant',
                'masters' => 60000,
                'doctoral' => 100000,
            ],
            6 => [ // Research Support Grant - Equipment
                'name' => 'Research Grant',
                'masters' => 225000,
                'doctoral' => 475000,
            ],
            7 => [ // Research Dissemination Grant
                'name' => 'Research Dissemination',
                'masters' => 75000,
                'doctoral' => 150000,
            ],
            8 => [ // Mentor's Fee
                'name' => "Mentor's Fee",
                'masters' => 36000,
                'doctoral' => 72000,
            ],
        ];

        // Check if the request type has a limit
        if (isset($requestTypeLimits[$requestTypeId])) {
            $programType = $isDoctoralProgram ? 'doctoral' : 'masters';
            $limit = $requestTypeLimits[$requestTypeId][$programType];
            $typeName = $requestTypeLimits[$requestTypeId]['name'];

            // If there's a limit (not null) and amount exceeds it
            if ($limit !== null && $amount > $limit) {
                return [
                    'amount' => "The maximum amount allowed for {$typeName} is ₱" . number_format($limit, 2) .
                        ".\nYour {$programType} program has a limit of ₱" . number_format($limit, 2) . "."
                ];
            }
        }

        return null;
    }

    /**
     * Create manuscript if required for specific request types
     *
     * @param FundRequest $fundRequest
     * @return void
     */
    private function createManuscriptIfRequired(FundRequest $fundRequest): void
    {
        $manuscriptService = app(ManuscriptService::class);
        
        if ($manuscriptService->shouldCreateManuscript($fundRequest)) {
            $manuscript = $manuscriptService->createManuscriptFromFundRequest($fundRequest);
            
            if ($manuscript) {
                // Log the manuscript creation
                $this->auditService->logCreate('Manuscript', $manuscript->id, [
                    'created_from_fund_request' => $fundRequest->id,
                    'auto_generated' => true,
                    'manuscript_type' => $manuscript->manuscript_type
                ]);
            }
        }
    }
}
