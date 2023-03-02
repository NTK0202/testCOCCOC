<?php

namespace App\Services;

use Illuminate\Contracts\Container\BindingResolutionException;

abstract class BaseService
{
    protected $repository;
    protected $model;

    /**
     * Create a new instance of BaseService
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->setRepository();
    }

    /**
     * Get the repository to be used by the service
     *
     * @return mixed
     */
    abstract public function getRepository(): mixed;

    /**
     * Set the repository to be used by the service
     *
     * @throws BindingResolutionException
     */
    public function setRepository(): void
    {
        $this->repository = app()->make(
            $this->getRepository()
        );
    }

    /**
     * Get all records from the repository
     *
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->repository->getAll();
    }

    /**
     * Get a single record by its ID from the repository
     *
     * @param  int  $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->repository->getById($id);
    }

    /**
     * Create a new record in the repository
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->repository->create($data);
    }

    /**
     * Update an existing record in the repository
     *
     * @param  int  $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data): mixed
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a record by its ID from the repository
     *
     * @param  int  $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->repository->delete($id);
    }
}
