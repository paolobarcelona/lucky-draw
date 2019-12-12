<?php

namespace Tests\Stubs\Repositories\Eloquent\ORM;

use App\Repositories\Eloquent\ORM\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

final class RepositoryStub extends AbstractRepository
{
    /**
     * RepositoryStub constructor.
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }
}
