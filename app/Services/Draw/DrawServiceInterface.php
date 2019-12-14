<?php

namespace App\Services\Draw;

interface DrawServiceInterface
{
    /**
     * Create a draw attempt.
     *
     * @param mixed[] $data
     *
     * @return \App\Services\Draw\DrawResponse
     */
    public function createDrawAttempt(array $data): DrawResponse;
}
