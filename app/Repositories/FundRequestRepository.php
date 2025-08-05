<?php

namespace App\Repositories;

use App\Models\FundRequest;
use Illuminate\Database\Eloquent\Collection;

class FundRequestRepository extends BaseRepository
{
    /**
     * FundRequestRepository constructor.
     *
     * @param FundRequest $model
     */
    public function __construct(FundRequest $model)
    {
        parent::__construct($model);
    }

    /**
     * Find fund requests by status
     *
     * @param string $status
     * @return Collection
     */
    public function findByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Find fund requests by user ID
     *
     * @param int $userId
     * @return Collection
     */
    public function findByUserId(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * Find fund requests by type
     *
     * @param string $type
     * @return Collection
     */
    public function findByType(string $type): Collection
    {
        return $this->model->where('request_type_id', $type)->get();
    }

    /**
     * Get pending fund requests
     *
     * @return Collection
     */
    public function getPendingRequests(): Collection
    {
        return $this->model->whereIn('status', [FundRequest::STATUS_SUBMITTED, FundRequest::STATUS_UNDER_REVIEW])->get();
    }

    /**
     * Get approved fund requests
     *
     * @return Collection
     */
    public function getApprovedRequests(): Collection
    {
        return $this->model->where('status', FundRequest::STATUS_APPROVED)->get();
    }

    /**
     * Get rejected fund requests
     *
     * @return Collection
     */
    public function getRejectedRequests(): Collection
    {
        return $this->model->where('status', FundRequest::STATUS_REJECTED)->get();
    }
}
