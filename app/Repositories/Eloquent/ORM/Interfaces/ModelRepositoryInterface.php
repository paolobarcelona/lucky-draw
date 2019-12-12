<?php

namespace App\Repositories\Eloquent\ORM\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ModelRepositoryInterface
{
    /**
     * Get the associated model.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(): Model;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return \App\Repositories\Eloquent\ORM\Interfaces\ModelRepositoryInterface
     */
    public function setModel(Model $model): self;

    /**
     * Eager load relationship.
     * 
     * @return mixed
     */
    public function with($relations);
}
