<?php

namespace App\Services\Draw;

use App\Exceptions\PrizeAlreadyExistsException;
use App\Models\DrawAttempt;
use App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;

final class DrawService implements DrawServiceInterface
{
    /**
     * @var \App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface
     */
    private $drawAttemptRepository;

    /**
     * @var \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var \App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface
     */
    private $winnerRepository;

    /**
     * @var \App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface
     */
    private $winningNumberRepository;

    /**
     * DrawService constructor.
     *
     * @param \App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface $drawAttemptRepository
     * @param \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface $userAttemptRepository
     * @param \App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface $winnerRepository
     * @param \App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface $winningNumberRepository
     */
    public function __construct(
        DrawAttemptRepositoryInterface $drawAttemptRepository,
        UserRepositoryInterface $userRepository,
        WinnerRepositoryInterface $winnerRepository,
        WinningNumberRepositoryInterface $winningNumberRepository
    ) {
        $this->drawAttemptRepository = $drawAttemptRepository;
        $this->userRepository = $userRepository;
        $this->winnerRepository = $winnerRepository;
        $this->winningNumberRepository = $winningNumberRepository;
    }

    /**
     * Create a draw attempt.
     *
     * @param mixed[] $data
     *
     * @return \App\Models\DrawAttempt
     *
     * @throws \App\Exceptions\PrizeAlreadyExistsException
     */
    public function createDrawAttempt(array $data): DrawAttempt
    {
        $prize = (string)$data['prize'];
        $winningNumberInput = $data['winning_number'] ?? null;
        $isGeneratedRandomly = (bool)$data['is_generated_randomly'];

        /** @var null|\App\Models\DrawAttempt $drawAttemptForPrize */
        $drawAttemptForPrize = $this->drawAttemptRepository->getWinningDrawAttemptByPrize($prize);

        if ($drawAttemptForPrize !== null) {
            throw new PrizeAlreadyExistsException(\config('exceptions.prize_already_exists'));
        }

        if ($isGeneratedRandomly === true) {
            $winningNumbers = $this->winningNumberRepository->getAllCountsGroupedByUserIdDescending();

            $userIds = [];

            foreach ($winningNumbers as $winningNumber) {
                // may laman na ba, at wala pa sa list? meaning skip na.
                if (empty($userIds) === false && \in_array($winningNumber->user_id, $userIds) === false) {
                    break;
                }

                $userIds[] = $winningNumber->user_id ?? '';
            }

            $allWinningNumbers = $this->winningNumberRepository->findByUserIds($userIds);
            $allNumbers = [];

            foreach ($allWinningNumbers as $winningNumber) {
                $allNumbers[] = (int)$winningNumber->winning_number;
            }

            $winningNumberInput = \array_rand($allNumbers, 1);
        }

        // TODO: throw exception if number already won (for fail safety).

        return $this->drawAttemptRepository->create([
            'prize' => $prize,
            'winning_number' => $winningNumberInput,
            'is_generated_randomly' => $isGeneratedRandomly
        ]);
    }
}
