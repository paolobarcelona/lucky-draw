<?php

namespace Tests\Unit\Repositories\Eloquent\ORM;

use App\Models\DrawAttempt;
use App\Repositories\Eloquent\ORM\DrawAttemptRepository;
use Mockery\MockInterface;
use Tests\AbstractTestCase;

/**
 * @covers \App\Repositories\Eloquent\ORM\DrawAttemptRepository
 */
final class DrawAttemptRepositoryTest extends AbstractTestCase
{
    /**
     * Should get all winning draw attempt by prize.
     *
     * @return void
     */
    public function testGetWinningDrawAttemptByPrize(): void
    {
        $prize = DrawAttempt::GRAND_PRIZE;

        /** @var \App\Models\DrawAttempt $model */
        $model = $this->mock(
            DrawAttempt::class,
            static function (MockInterface $mock) use ($prize): void {
                $mock->shouldReceive('where')
                    ->once()
                    ->with('prize', '=', $prize)
                    ->andReturnSelf();
                $mock->shouldReceive('has')
                    ->once()
                    ->with('winner')
                    ->andReturnSelf();
                $mock->shouldReceive('first')
                    ->once()
                    ->withNoArgs()
                    ->andReturn(new DrawAttempt());
            }
        );

        self::assertInstanceOf(
            DrawAttempt::class,
            (new DrawAttemptRepository($model))->getWinningDrawAttemptByPrize($prize)
        );
    }

    /**
     * Should get all winning draw attempt by number.
     *
     * @return void
     */
    public function testGetWinningDrawAttemptByNumber(): void
    {
        $winningNumber = $this->getFaker()->unique()->randomNumber(4, true);

        /** @var \App\Models\DrawAttempt $model */
        $model = $this->mock(
            DrawAttempt::class,
            static function (MockInterface $mock) use ($winningNumber): void {
                $mock->shouldReceive('where')
                    ->once()
                    ->with('winning_number', '=', $winningNumber)
                    ->andReturnSelf();
                $mock->shouldReceive('has')
                    ->once()
                    ->with('winner')
                    ->andReturnSelf();
                $mock->shouldReceive('first')
                    ->once()
                    ->withNoArgs()
                    ->andReturn(new DrawAttempt());
            }
        );

        self::assertInstanceOf(
            DrawAttempt::class,
            (new DrawAttemptRepository($model))->getWinningDrawAttemptByNumber($winningNumber)
        );
    }
}
