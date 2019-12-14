<?php

namespace App\Services\Draw;

use App\Exceptions\PrizeAlreadyExistsException;
use App\Models\DrawAttempt;
use App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;
use App\Exceptions\NoWinningNumbersFoundException;

final class DrawService implements DrawServiceInterface
{
    /**
     * @var \App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface
     */
    private $drawAttemptRepository;

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
     * @param \App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface $winnerRepository
     * @param \App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface $winningNumberRepository
     */
    public function __construct(
        DrawAttemptRepositoryInterface $drawAttemptRepository,
        WinnerRepositoryInterface $winnerRepository,
        WinningNumberRepositoryInterface $winningNumberRepository
    ) {
        $this->drawAttemptRepository = $drawAttemptRepository;
        $this->winnerRepository = $winnerRepository;
        $this->winningNumberRepository = $winningNumberRepository;
    }

    /**
     * Create a draw attempt.
     *
     * @param mixed[] $data
     *
     * @return \App\Services\Draw\DrawResponse
     *
     * @throws \App\Exceptions\NoWinningNumbersFoundException
     * @throws \App\Exceptions\PrizeAlreadyExistsException
     */
    public function createDrawAttempt(array $data): DrawResponse
    {
        $prize = $data['prize'] ?? '';
        $winningNumberInput = $data['winning_number'] ?? null;
        $isGeneratedRandomly = (bool)($data['is_generated_randomly'] ?? null);

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

                $winningNumberUserReverse[$winningNumber->winning_number] = [
                    'winning_number_id' => (int)$winningNumber->id,
                    'user_id' => (int)$winningNumber->user_id
                ];
            }

            if (empty($allNumbers) === true) {
                throw new NoWinningNumbersFoundException(\config('exceptions.no_winning_numbers_found'));
            }

            $randomKey = \array_rand($allNumbers);
            $winningNumberInput = $allNumbers[$randomKey];

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

            return new DrawResponse(
                $drawAttempt,
                $winner->user()->first() ?? null,
                $winner
            );
        }

        // TODO: CHECK IF THERE IS WINNING NUMBER FOR THE WINNING NUMBER INPUT.
        $draw = $this->drawAttemptRepository->create([
            'prize' => $prize,
            'winning_number' => $winningNumberInput,
            'is_generated_randomly' => $isGeneratedRandomly
        ]);

        /** @var null|\App\Models\WinningNumber $winningNumberFromNumber */
        $winningNumberFromNumber = $this->winningNumberRepository->findBy([
            'winning_number' => $winningNumberInput
        ]);

        if ($winningNumberFromNumber->count() === 0) {
            return new DrawResponse($draw);
        }

        /** @var null|\App\Models\WinningNumber $numberWithoutWinner */
        $numberWithoutWinner = $this->winningNumberRepository
            ->getWinningNumberWithoutWinnerByNumber($winningNumberInput);

        // If this is null, it means that there is already a winner for this number.
        if ($numberWithoutWinner === null) {
            throw new PrizeAlreadyExistsException(\config('exceptions.prize_already_exists_for_number'));
        }

        $userId = $numberWithoutWinner->user()->first()->id ?? null;

        /** @var null|\App\Models\Winner $winnerByUser */
        $winnerByUser = $this->winnerRepository->findBy([
            'user_id' => $userId
        ]);

        if ($winnerByUser->count() !== 0) {
            throw new PrizeAlreadyExistsException(
                \sprintf(
                    \config('exceptions.prize_already_exists_for_name'),
                    $numberWithoutWinner->user()->first()->name
                )
            );
        }

        $winner = $this->winnerRepository->create([
            'draw_attempt_id' => $draw->id ?? null,
            'user_id' => $userId,
            'winning_number_id' => $numberWithoutWinner->id ?? null
        ]);

        return new DrawResponse($draw, $winner->user()->first(), $winner);
    }
}
