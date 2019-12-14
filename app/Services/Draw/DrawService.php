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
                if (empty($userIds) === false && \in_array($winningNumber->user_id, $userIds) === false) {
                    break;
                }

                $userIds[] = $winningNumber->user_id ?? '';
            }

            $allWinningNumbers = $this->winningNumberRepository->findByUserIds($userIds);
            $allNumbers = $winningNumberUserReverse = [];

            foreach ($allWinningNumbers as $winningNumber) {
                $number = (int)$winningNumber->winning_number;

                $allNumbers[] = $number;

                if (isset($winningNumberUserReverse[$winningNumber->winning_number]) === false ) {
                    $winningNumberUserReverse[$winningNumber->winning_number] = [
                        'winning_number_id' => (int)$winningNumber->id,
                        'user_id' => (int)$winningNumber->user_id
                    ];
                }
            }

            $winningNumberInput = \array_rand($allNumbers, 1);

            // for here, we want to create an attempt and a winner immediately.
            $drawAttempt = $this->drawAttemptRepository->create([
                'prize' => $prize,
                'winning_number' => $winningNumberInput,
                'is_generated_randomly' => $isGeneratedRandomly
            ]);

            $winner = $this->winnerRepository->create([
                'draw_attempt_id' => $drawAttempt->id,
                'user_id' => $winningNumberUserReverse[$winningNumberInput]['user_id'] ?? null,
                'winning_number_id' => $winningNumberUserReverse[$winningNumberInput]['winning_number_id'] ?? null
            ]);

            return $drawAttempt;
        }

        /** @var null|\App\Models\WinningNumber $numberWithoutWinner */
        $numberWithoutWinner = $this->winningNumberRepository
            ->getWinningNumberWithoutWinnerByNumber($winningNumberInput);

        if ($numberWithoutWinner !== null) {
            throw new PrizeAlreadyExistsException(\config('exceptions.prize_already_exists_for_number'));
        }

        // TODO: CHECK IF THERE IS WINNING NUMBER FOR THE WINNING NUMBER INPUT.
        $draw = $this->drawAttemptRepository->create([
            'prize' => $prize,
            'winning_number' => $winningNumberInput,
            'is_generated_randomly' => $isGeneratedRandomly
        ]);

        $winner = $this->winnerRepository->create([
            'draw_attempt_id' => $draw->id,
            'user_id' => $numberWithoutWinner->user()->id ?? null,
            'winning_number_id' => $numberWithoutWinner->id ?? null
        ]);

        return $draw;
    }
}
