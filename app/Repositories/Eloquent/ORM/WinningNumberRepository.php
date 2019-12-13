<?php

namespace App\Repositories\Eloquent\ORM;

use App\Models\WinningNumber;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

final class WinningNumberRepository extends AbstractRepository implements WinningNumberRepositoryInterface
{
    /**
     * WinningNumberRepository constructor.
     *
     * @param \App\Models\WinningNumber $winningNumber
     */
    public function __construct(WinningNumber $winningNumber)
    {
        parent::__construct($winningNumber);
    }

    /**
     * Find winning numbers by user ids.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByUserIds(array $ids): Collection
    {
        if (empty($ids)) {
            return new Collection();
        }

        return $this->model
            ->whereIn('user_id', $ids)
            ->whereNotIn('winning_number', function($query) {
                $query->select('winning_number')
                    ->from('draw_attempts')
                    ->join('winners', 'draw_attempts.id', '=', 'winners.draw_attempt_id');
            })
            ->get();
    }

    /**
     * Get all winning number counts based on user id, descending order.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllCountsGroupedByUserIdDescending(): SupportCollection
    {
        $records = $this->model
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->get();

        return $records;
    }
}
