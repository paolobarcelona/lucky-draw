<?php

namespace App\Repositories\Eloquent\ORM;

use App\Models\DrawAttempt;
use App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface;

final class DrawAttemptRepository extends AbstractRepository implements DrawAttemptRepositoryInterface
{
    /**
     * DrawAttemptRepository constructor.
     *
     * @param \App\Models\DrawAttempt $drawAttempt
     */
    public function __construct(DrawAttempt $drawAttempt)
    {
        parent::__construct($drawAttempt);
    }

    /**
     * Get a draw attempt that has a winner by prize.
     *
     * @param string $prize
     *
     * @return null|\App\Models\DrawAttempt
     */
    public function getWinningDrawAttemptByPrize(string $prize): ?DrawAttempt
    {
        /** @var null|\App\Models\DrawAttempt $drawAttempt */
        $drawAttempt = $this->model
            ->where('prize', '=', $prize)
            ->has('winner')
            ->first() ?? null;

        return $drawAttempt;
    }

    /**
     * Get a draw attempt that has a winner by winning number.
     *
     * @param int $winningNumber
     *
     * @return null|\App\Models\DrawAttempt
     */
    public function getWinningDrawAttemptByNumber(int $winningNumber): ?DrawAttempt
    {
        /** @var null|\App\Models\DrawAttempt $drawAttempt */
        $drawAttempt = $this->model
            ->where('winning_number', '=', $winningNumber)
            ->has('winner')
            ->first() ?? null;

        return $drawAttempt;
    }
}
