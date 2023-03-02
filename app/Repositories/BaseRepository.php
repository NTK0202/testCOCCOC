<?php

namespace App\Repositories;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class BaseRepository
{
    protected $model;

    /**
     * Instantiate a new BaseRepository instance.
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get the model name, which should be implemented in child classes.
     *
     * @return mixed
     */
    abstract public function getModel(): mixed;

    /**
     * Dynamically set model property based on the given model name.
     *
     * @throws BindingResolutionException
     */
    public function setModel(): void
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Return all instances of the associated model.
     *
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->model->all();
    }

    /**
     * Get model by ID.
     *
     * @param  int  $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new model instance.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        $this->model->fill($data);

        return $this->model->create($data);
    }

    /**
     * Update model instance by ID.
     *
     * @param  int  $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data): mixed
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    /**
     * Delete model instance by ID.
     *
     * @param  int  $id
     * @return void
     * @throws Exception
     */
    public function delete(int $id): void
    {
        $model = $this->model->findOrFail($id);
        $model->delete();
    }

}
