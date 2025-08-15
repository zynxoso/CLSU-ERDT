<?php

namespace App\Services\Interfaces;

use App\Models\FundRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface FundRequestServiceInterface
{
    /**
     * Get fund requests for admin with filtering
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getAdminFundRequests(Request $request): LengthAwarePaginator;

    /**
     * Get fund requests for scholar with filtering
     *
     * @param Request $request
     * @param int $scholarProfileId
     * @return LengthAwarePaginator
     */
    public function getScholarFundRequests(Request $request, int $scholarProfileId): LengthAwarePaginator;

    /**
     * Get fund request statistics for scholar
     *
     * @param int $scholarProfileId
     * @return array
     */
    public function getScholarFundRequestStatistics(int $scholarProfileId): array;

    /**
     * Create a new fund request
     *
     * @param array $data
     * @param int $scholarProfileId
     * @param Request $request
     * @return FundRequest
     */
    public function createFundRequest(array $data, int $scholarProfileId, Request $request): FundRequest;

    /**
     * Update a fund request
     *
     * @param FundRequest $fundRequest
     * @param array $data
     * @return FundRequest
     */
    public function updateFundRequest(FundRequest $fundRequest, array $data): FundRequest;

    /**
     * Submit a fund request for review
     *
     * @param FundRequest $fundRequest
     * @return FundRequest
     */
    public function submitFundRequest(FundRequest $fundRequest): FundRequest;

    /**
     * Approve a fund request
     *
     * @param FundRequest $fundRequest
     * @param int $reviewerId
     * @return FundRequest
     */
    public function approveFundRequest(FundRequest $fundRequest, int $reviewerId): FundRequest;

    /**
     * Reject a fund request
     *
     * @param FundRequest $fundRequest
     * @param string $notes
     * @param int $reviewerId
     * @return FundRequest
     */
    public function rejectFundRequest(FundRequest $fundRequest, string $notes, int $reviewerId): FundRequest;

    /**
     * Mark a fund request as under review
     *
     * @param FundRequest $fundRequest
     * @return FundRequest
     */
    public function markFundRequestAsUnderReview(FundRequest $fundRequest): FundRequest;

    /**
     * Get status updates for fund requests
     *
     * @param array $requestIds
     * @param int $scholarProfileId
     * @return array
     */
    public function getFundRequestStatusUpdates(array $requestIds, int $scholarProfileId): array;

    /**
     * Validate fund request amount against limits
     *
     * @param int $requestTypeId
     * @param float $amount
     * @param string|null $degreeLevel
     * @return array|null Returns error array if validation fails, null if passes
     */
    public function validateFundRequestAmount(int $requestTypeId, float $amount, ?string $degreeLevel): ?array;

    /**
     * Check for active duplicate fund requests
     *
     * @param int $scholarProfileId
     * @param int $requestTypeId
     * @return array|null Returns error array if duplicate found, null if no duplicates
     */
    public function checkForActiveDuplicateRequest(int $scholarProfileId, int $requestTypeId): ?array;
}
