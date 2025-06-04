<?php

namespace App\Repositories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;

class DocumentRepository extends BaseRepository
{
    /**
     * DocumentRepository constructor.
     *
     * @param Document $model
     */
    public function __construct(Document $model)
    {
        parent::__construct($model);
    }

    /**
     * Find documents by user ID
     *
     * @param int $userId
     * @return Collection
     */
    public function findByUserId(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * Find documents by fund request ID
     *
     * @param int $fundRequestId
     * @return Collection
     */
    public function findByFundRequestId(int $fundRequestId): Collection
    {
        return $this->model->where('fund_request_id', $fundRequestId)->get();
    }

    /**
     * Find documents by type
     *
     * @param string $type
     * @return Collection
     */
    public function findByType(string $type): Collection
    {
        return $this->model->where('document_type', $type)->get();
    }

    /**
     * Get documents that need approval
     *
     * @return Collection
     */
    public function getDocumentsNeedingApproval(): Collection
    {
        return $this->model->where('status', 'pending')->get();
    }
}
