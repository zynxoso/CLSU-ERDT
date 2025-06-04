<?php

namespace App\Repositories;

use App\Models\ScholarProfile;
use Illuminate\Database\Eloquent\Collection;

class ScholarProfileRepository extends BaseRepository
{
    /**
     * ScholarProfileRepository constructor.
     *
     * @param ScholarProfile $model
     */
    public function __construct(ScholarProfile $model)
    {
        parent::__construct($model);
    }

    /**
     * Find scholar profile by user ID
     *
     * @param int $userId
     * @return ScholarProfile|null
     */
    public function findByUserId(int $userId): ?ScholarProfile
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Find scholar profiles by university
     *
     * @param string $university
     * @return Collection
     */
    public function findByUniversity(string $university): Collection
    {
        return $this->model->where('university', $university)->get();
    }

    /**
     * Find scholar profiles by program
     *
     * @param string $program
     * @return Collection
     */
    public function findByProgram(string $program): Collection
    {
        return $this->model->where('program', $program)->get();
    }

    /**
     * Find scholar profiles by status
     *
     * @param string $status
     * @return Collection
     */
    public function findByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Get active scholars
     *
     * @return Collection
     */
    public function getActiveScholars(): Collection
    {
        return $this->model->where('status', 'active')->get();
    }

    /**
     * Get graduated scholars
     *
     * @return Collection
     */
    public function getGraduatedScholars(): Collection
    {
        return $this->model->where('status', 'graduated')->get();
    }
}
