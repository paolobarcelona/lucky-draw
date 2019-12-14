<?php

namespace Tests\Unit\Repositories\Eloquent\ORM;

use App\Models\WinningNumber;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;
use App\Repositories\Eloquent\ORM\WinningNumberRepository;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use Tests\AbstractTestCase;

/**
 * @covers \App\Repositories\Eloquent\ORM\WinningNumberRepository
 */
final class WinningNumberRepositoryTest extends AbstractTestCase
{
    /**
     * Should find records by user ids.
     *
     * @return void
     */
    public function testFindByUserIds(): void
    {
        $ids = [1];

        /** @var \App\Models\WinningNumber $model */
        $model = $this->mock(
            WinningNumber::class,
            static function (MockInterface $mock) use ($ids): void {
                $mock->shouldReceive('whereIn')
                    ->once()
                    ->with('user_id', $ids)
                    ->andReturnSelf();
                $mock->shouldReceive('whereNotIn')
                    ->once()
                    ->withArgs(function($field, $closure): bool {
                        self::assertEquals('winning_number', $field);
                        self::assertIsCallable($closure);

                        return true;
                    })
                    ->andReturnSelf();
                $mock->shouldReceive('get')
                    ->once()
                    ->withNoArgs()
                    ->andReturn(new Collection([new WinningNumber()]));
            }
        );

        $results = (new WinningNumberRepository($model))->findByUserIds($ids);

        self::assertInstanceOf(Collection::class, $results);
        self::assertEquals(1, $results->count());
    }

    /**
     * Should get all winning number counts based on user id, descending order.
     *
     * @return void
     */
    public function testGetAllCountsGroupedByUserIdDescending(): void
    {
        /** @var \App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface $repository */
        $repository = $this->getRepository(WinningNumberRepositoryInterface::class);

        $items = $repository->getAllCountsGroupedByUserIdDescending();

        self::assertInstanceOf(Collection::class, $items);
    }

    /**
     * Should get all winning number by number that does not have a winner.
     *
     * @return void
     */
    public function testGetWinningNumberWithoutWinnerByNumber(): void
    {
        $winningNumber = $this->getFaker()->unique()->randomNumber(4, true);

        /** @var \App\Models\WinningNumber $model */
        $model = $this->mock(
            WinningNumber::class,
            static function (MockInterface $mock) use ($winningNumber): void {
                $mock->shouldReceive('where')
                    ->once()
                    ->with('winning_number', '=', $winningNumber)
                    ->andReturnSelf();
                $mock->shouldReceive('has')
                    ->once()
                    ->with('winner', '<', '1')
                    ->andReturnSelf();
                $mock->shouldReceive('first')
                    ->once()
                    ->withNoArgs()
                    ->andReturnNull();
            }
        );

        self::assertNull(
            (new WinningNumberRepository($model))->getWinningNumberWithoutWinnerByNumber($winningNumber)
        );
    }
}
