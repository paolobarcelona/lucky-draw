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
}
