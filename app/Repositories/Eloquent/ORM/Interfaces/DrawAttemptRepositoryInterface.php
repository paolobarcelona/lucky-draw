<?php

namespace App\Repositories\Eloquent\ORM\Interfaces;

use App\Models\DrawAttempt;

interface DrawAttemptRepositoryInterface
{
    /**
     * Get a draw attempt that has a winner by prize.
     *
     * @param string $prize
     *
     * @return null|\App\Models\DrawAttempt
     */
    public function getWinningDrawAttemptByPrize(string $prize): ?DrawAttempt;
}
