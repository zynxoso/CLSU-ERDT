<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all resources
     *
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*']);

    /**
     * Get paginated resources
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate(int $perPage = 15, array $columns = ['*']);

    /**
     * Create a new resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a resource
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id);

    /**
     * Delete a resource
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * Find a resource by id
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find(int $id, array $columns = ['*']);

    /**
     * Find a resource by specific criteria
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return mixed
     */
    public function findBy(string $field, $value, array $columns = ['*']);
}
