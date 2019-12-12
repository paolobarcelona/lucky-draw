<?php

namespace App\Repositories;

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
     * @return mixed
     */
    public function show(string $id);

    /**
     * Update record.
     *
     * @return mixed
     */
    public function update(array $data, string $id);
}
