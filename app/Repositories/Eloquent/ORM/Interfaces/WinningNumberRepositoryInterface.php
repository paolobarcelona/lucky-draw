<?php

namespace App\Repositories\Eloquent\ORM\Interfaces;

use App\Models\WinningNumber;
use Illuminate\Database\Eloquent\Collection;

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCountsGroupedByUserIdDescending(): Collection;

    /**
     * Get a winning number by number that has no winner yet.
     *
     * @param int $number
     *
     * @return null|\App\Models\WinningNumber
     */
    public function getWinningNumberWithoutWinnerByNumber(int $number): ?WinningNumber;
}
