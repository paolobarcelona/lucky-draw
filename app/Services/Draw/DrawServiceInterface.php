<?php

namespace App\Services\Draw;

use App\Models\DrawAttempt;

interface DrawServiceInterface
{
    /**
     * Create a draw attempt.
     *
     * @param mixed[] $data
     *
     * @return \App\Models\DrawAttempt
     */
    public function createDrawAttempt(array $data): DrawAttempt;
}
