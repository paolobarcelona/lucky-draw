<?php

namespace App\Repositories\Eloquent\ORM;

use App\Models\WinningNumber;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;

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
}
