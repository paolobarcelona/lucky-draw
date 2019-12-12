<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class ModelStub extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name'];

    /**
     * Test relation
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function relations(): Collection
    {
        return new Collection();
    }
}
