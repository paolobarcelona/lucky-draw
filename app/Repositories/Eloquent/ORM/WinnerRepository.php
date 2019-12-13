<?php

namespace App\Repositories\Eloquent\ORM;

use App\Models\Winner;
use App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface;

final class WinnerRepository extends AbstractRepository implements WinnerRepositoryInterface
{
    /**
     * WinnerRepository constructor.
     * 
     * @param \App\Models\Winner $winner
     */
    public function __construct(Winner $winner)
    {
        parent::__construct($winner);   
    }
}
