<?php

namespace App\Repositories\Eloquent\ORM;

use App\Repositories\AppRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\ModelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements AppRepositoryInterface, ModelRepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * AbstractRepository 
     *
     * \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Return all records.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Create record.
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Delete record.
     * 
     * @return mixed
     */
    public function delete(string $id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Get the associated model.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }    

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return \App\Repositories\Eloquent\ORM\Interfaces\ModelRepositoryInterface
     */
    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Find record.
     * 
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function find(string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find records by filters
     * 
     * @return null|mixed
     */
    public function findBy(array $filters)
    {
        if (empty($filters) === true) {
            return new Collection();
        }

        foreach ($filters as $field => $value) {
            $this->model = $this->model->where($field, '=', $value);
        }

        return $this->model->get();
    }    

    /**
     * Find a record or fail.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update record.
     *
     * @return mixed
     */
    public function update(array $data, string $id)
    {
        $record = $this->model->findOrFail($id);

        $record->update($data);

        return $record;
    }
    
    /**
     * Eager load relationship.
     * 
     * @return mixed
     */
    public function with($relations)
    {
        return $this->model->with($relations);
    }    
}
