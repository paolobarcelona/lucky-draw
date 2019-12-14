<?php

namespace App\Services\Draw;

use App\Models\DrawAttempt;
use App\Models\User;
use App\Models\Winner;

final class DrawResponse
{
    /**
     * @var null|\App\Models\DrawAttempt
     */

    private $drawAttempt;
    /**
     * @var null|\App\Models\User
     */
    private $user;

    /**
     * @var null|\App\Models\Winner
     */
    private $winner;

    /**
     * DrawResponse Controller.
     *
     * @param null|\App\Models\DrawAttempt $drawAttempt
     * @param null|\App\Models\User $user
     * @param null|\App\Models\Winner $winner
     */
    public function __construct(
        ?DrawAttempt $drawAttempt = null,
        ?User $user = null,
        ?Winner $winner = null
    ) {
        $this->drawAttempt = $drawAttempt;
        $this->user = $user;
        $this->winner = $winner;
    }

    /**
     * @return null|\App\Models\DrawAttempt
     */
    public function getDrawAttempt(): ?DrawAttempt
    {
        return $this->drawAttempt;
    }

    /**
     * @return null|\App\Models\User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return null|\App\Models\Winner
     */
    public function getWinner(): ?Winner
    {
        return $this->winner;
    }
}
