<?php

namespace App\Repositories\Eloquent\ORM\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Get all non admin users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllNonAdminUsers(): Collection;
}
