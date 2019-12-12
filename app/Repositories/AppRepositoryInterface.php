<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface AppRepositoryInterface
{
    /**
     * Return all records.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create record.
     *
     * @return mixed
     */
    public function create(array $data);

    /**
     * Delete record.
     * 
     * @return mixed
     */
    public function delete(string $id);

    /**
     * Show record
     * 
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function find(string $id): ?Model;
    
    /**
     * Show record
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(string $id): Model;

    /**
     * Update record.
     *
     * @return mixed
     */
    public function update(array $data, string $id);
}
