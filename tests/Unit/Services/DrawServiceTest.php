<?php

namespace Tests\Unit\Services;

use App\Exceptions\PrizeAlreadyExistsException;
use App\Models\DrawAttempt;
use App\Models\Winner;
use App\Models\WinningNumber;
use App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;
use App\Services\Draw\DrawService;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use App\Exceptions\NoWinningNumbersFoundException;
use App\Services\Draw\DrawResponse;
use Tests\AbstractTestCase;

/**
 * @covers \App\Services\DrawService
 */
final class DrawServiceTest extends AbstractTestCase
{
    /**
     * Should throw PrizeAlreadyExistsException if there is already a winner against the prize.
     *
     * @return void
     */
    public function testCreateDrawAttemptWithPrizeThrowPrizeAlreadyExistsException(): void
    {
        $this->expectException(PrizeAlreadyExistsException::class);

        $payload = [
            'is_generated_randomly' => false,
            'prize' => DrawAttempt::GRAND_PRIZE,
            'winning_number' => $this->getFaker()->unique()->randomNumber(4, true)
        ];

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface $drawAttemptRepo */
        $drawAttemptRepo = $this->mock(
            DrawAttemptRepositoryInterface::class,
            static function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getWinningDrawAttemptByPrize')
                    ->once()
                    ->with((string)$payload['prize'])
                    ->andReturn(new DrawAttempt());
            }
        );

        (new DrawService(
            $drawAttemptRepo,
            $this->app->get(WinnerRepositoryInterface::class),
            $this->app->get(WinningNumberRepositoryInterface::class)
        ))->createDrawAttempt($payload);
    }

    /**
     * Should throw PrizeAlreadyExistsException if there is already a winner for the number.
     *
     * @return void
     */
    public function testCreateDrawAttemptWithNumberThrowPrizeAlreadyExistsException(): void
    {
        $this->expectException(PrizeAlreadyExistsException::class);

        $payload = [
            'is_generated_randomly' => false,
            'prize' => DrawAttempt::GRAND_PRIZE,
            'winning_number' => (int)$this->getFaker()->unique()->randomNumber(4, true)
        ];

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface $drawAttemptRepo */
        $drawAttemptRepo = $this->mock(
            DrawAttemptRepositoryInterface::class,
            static function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getWinningDrawAttemptByPrize')
                    ->once()
                    ->with((string)$payload['prize'])
                    ->andReturnNull();
                $mock->shouldReceive('create')
                    ->once()
                    ->with($payload)
                    ->andReturn(new DrawAttempt($payload));
            }
        );

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface $winningNumberRepository */
        $winningNumberRepository = $this->mock(
            WinningNumberRepositoryInterface::class,
            static function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getWinningNumberWithoutWinnerByNumber')
                    ->once()
                    ->with((int)$payload['winning_number'])
                    ->andReturnNull();
                $mock->shouldReceive('findBy')
                    ->once()
                    ->with(['winning_number' => $payload['winning_number']])
                    ->andReturn(new Collection([new WinningNumber(), new WinningNumber()]));
            }
        );

        $createdDraw = (new DrawService(
            $drawAttemptRepo,
            $this->app->get(WinnerRepositoryInterface::class),
            $winningNumberRepository
        ))->createDrawAttempt($payload);

        self::assertEquals($payload['is_generated_randomly'], $createdDraw->is_generated_randomly ?? null);
        self::assertEquals($payload['winning_number'], $createdDraw->winning_number ?? null);
        self::assertEquals($payload['prize'], $createdDraw->prize ?? null);
    }

    /**
     * Should create a draw attempt with manual number.
     *
     * @return void
     */
    public function testCreateDrawAttemptWithManualNumberSuccess(): void
    {
        $payload = [
            'is_generated_randomly' => false,
            'prize' => DrawAttempt::GRAND_PRIZE,
            'winning_number' => $this->getFaker()->unique()->randomNumber(4, true)
        ];

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface $drawAttemptRepo */
        $drawAttemptRepo = $this->mock(
            DrawAttemptRepositoryInterface::class,
            static function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getWinningDrawAttemptByPrize')
                    ->once()
                    ->with((string)$payload['prize'])
                    ->andReturnNull();
                $mock->shouldReceive('create')
                    ->once()
                    ->with($payload)
                    ->andReturn(new DrawAttempt($payload));
            }
        );

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface $winnerRepository */
        $winnerRepository = $this->mock(
            WinnerRepositoryInterface::class,
            function (MockInterface $mock): void {
                $mock->shouldReceive('create')
                    ->once()
                    ->withArgs(function ($data): bool {
                        $this->assertArrayHasKeys(
                            ['draw_attempt_id', 'user_id', 'winning_number_id'],
                            $data
                        );

                        return true;
                    })
                    ->andReturn(new Winner());
            }
        );

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface $winningNumberRepository */
        $winningNumberRepository = $this->mock(
            WinningNumberRepositoryInterface::class,
            static function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getWinningNumberWithoutWinnerByNumber')
                    ->once()
                    ->with((int)$payload['winning_number'])
                    ->andReturn(new WinningNumber());
                $mock->shouldReceive('findBy')
                    ->once()
                    ->with(['winning_number' => $payload['winning_number']])
                    ->andReturn(new Collection([new WinningNumber(), new WinningNumber()]));
            }
        );

        $drawResponse =  (new DrawService(
            $drawAttemptRepo,
            $winnerRepository,
            $winningNumberRepository
        ))->createDrawAttempt($payload);

        $createdDraw = $drawResponse->getDrawAttempt();

        self::assertInstanceOf(DrawResponse::class, $drawResponse);
        self::assertEquals($payload['is_generated_randomly'], $createdDraw->is_generated_randomly ?? null);
        self::assertEquals($payload['winning_number'], $createdDraw->winning_number ?? null);
        self::assertEquals($payload['prize'], $createdDraw->prize ?? null);
    }

    /**
     * Should create a draw attempt with auto number.
     *
     * @return void
     */
    public function testCreateDrawAttemptWithAutoNumberSuccess(): void
    {
        $payload = [
            'is_generated_randomly' => true,
            'prize' => DrawAttempt::GRAND_PRIZE,
            'winning_number' => $this->getFaker()->unique()->randomNumber(4, true)
        ];

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface $drawAttemptRepo */
        $drawAttemptRepo = $this->mock(
            DrawAttemptRepositoryInterface::class,
            function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getWinningDrawAttemptByPrize')
                    ->once()
                    ->with((string)$payload['prize'])
                    ->andReturnNull();
                $mock->shouldReceive('create')
                    ->once()
                    ->withArgs(function($data): bool {
                        self::assertIsArray($data);

                        $this->assertArrayHasKeys(
                            ['is_generated_randomly', 'prize', 'winning_number'],
                            $data
                        );

                        return true;
                    })
                    ->andReturn(new DrawAttempt($payload));
            }
        );

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface $winnerRepository */
        $winnerRepository = $this->mock(
            WinnerRepositoryInterface::class,
            function (MockInterface $mock): void {
                $mock->shouldReceive('create')
                    ->once()
                    ->withArgs(function ($data): bool {
                        $this->assertArrayHasKeys(
                            ['draw_attempt_id', 'user_id', 'winning_number_id'],
                            $data
                        );

                        return true;
                    })
                    ->andReturn(new Winner());
            }
        );

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface $winningNumberRepository */
        $winningNumberRepository = $this->mock(
            WinningNumberRepositoryInterface::class,
            static function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getAllCountsGroupedByUserIdDescending')
                    ->once()
                    ->withNoArgs()
                    ->andReturn(new Collection([new WinningNumber(), new WinningNumber()]));
                $mock->shouldReceive('findByUserIds')
                    ->once()
                    ->withArgs(function($data): bool {
                        return \is_array($data) === true;
                    })
                    ->andReturn(new Collection([new WinningNumber(), new WinningNumber()]));
            }
        );

        $drawResponse = (new DrawService(
            $drawAttemptRepo,
            $winnerRepository,
            $winningNumberRepository
        ))->createDrawAttempt($payload);

        $createdDraw = $drawResponse->getDrawAttempt();
        
        self::assertEquals($payload['is_generated_randomly'], $createdDraw->is_generated_randomly ?? null);
        self::assertEquals($payload['winning_number'], $createdDraw->winning_number ?? null);
        self::assertEquals($payload['prize'], $createdDraw->prize ?? null);
    }

    /**
     * Should create a draw attempt with auto number.
     *
     * @return void
     */
    public function testCreateDrawAttemptWithAutoNumberThrowNoWinningNumbersFoundException(): void
    {
        $this->expectException(NoWinningNumbersFoundException::class);

        $payload = [
            'is_generated_randomly' => true,
            'prize' => DrawAttempt::GRAND_PRIZE,
            'winning_number' => $this->getFaker()->unique()->randomNumber(4, true)
        ];

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface $drawAttemptRepo */
        $drawAttemptRepo = $this->mock(
            DrawAttemptRepositoryInterface::class,
            function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getWinningDrawAttemptByPrize')
                    ->once()
                    ->with((string)$payload['prize'])
                    ->andReturnNull();
            }
        );

        /** @var \App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface $winningNumberRepository */
        $winningNumberRepository = $this->mock(
            WinningNumberRepositoryInterface::class,
            static function (MockInterface $mock) use ($payload): void {
                $mock->shouldReceive('getAllCountsGroupedByUserIdDescending')
                    ->once()
                    ->withNoArgs()
                    ->andReturn(new Collection([new WinningNumber(), new WinningNumber()]));
                $mock->shouldReceive('findByUserIds')
                    ->once()
                    ->withArgs(function($data): bool {
                        return \is_array($data) === true;
                    })
                    ->andReturn(new Collection());
            }
        );

        (new DrawService(
            $drawAttemptRepo,
            $this->app->get(WinnerRepositoryInterface::class),
            $winningNumberRepository
        ))->createDrawAttempt($payload);
    }
}
