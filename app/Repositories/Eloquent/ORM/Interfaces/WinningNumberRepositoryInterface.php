<?php

namespace App\Repositories\Eloquent\ORM\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

interface WinningNumberRepositoryInterface
{
    /**
     * Find winning numbers by user ids.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByUserIds(array $ids): Collection;

    /**
     * Get all winning number counts based on user id, descending order.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllCountsGroupedByUserIdDescending(): SupportCollection;
}
