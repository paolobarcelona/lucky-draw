<?php

namespace App\Services\Draw;

use App\Models\DrawAttempt;
use App\Models\User;
use App\Models\Winner;

final class DrawResponse
{
    /**
     * @var \App\Models\DrawAttempt
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
     * @param \App\Models\DrawAttempt $drawAttempt
     * @param \App\Models\User $user
     * @param \App\Models\Winner $winner
     */
    public function __construct(
        DrawAttempt $drawAttempt,
        ?User $user = null,
        ?Winner $winner = null
    ) {
        $this->drawAttempt = $drawAttempt;
        $this->user = $user;
        $this->winner = $winner;
    }

    /**
     * @return \App\Models\DrawAttempt
     */
    public function getDrawAttempt(): DrawAttempt
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
