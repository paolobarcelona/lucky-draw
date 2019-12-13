<?php

namespace Tests\Unit\Providers;

use App\Repositories\Eloquent\ORM\Interfaces\DrawAttemptRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;
use Tests\AbstractTestCase;

/**
 * @covers \App\Providers\AppRepositoryServiceProvider
 */
final class AppRepositoryServiceProviderTest extends AbstractTestCase
{
    /**
     * Should Register all services into the container.
     *
     * @return void
     */
    public function testRegister(): void
    {
        self::assertInstanceOf(
            UserRepositoryInterface::class,
            $this->app->make(UserRepositoryInterface::class)
        );

        self::assertInstanceOf(
            WinningNumberRepositoryInterface::class,
            $this->app->make(WinningNumberRepositoryInterface::class)
        );

        self::assertInstanceOf(
            DrawAttemptRepositoryInterface::class,
            $this->app->make(DrawAttemptRepositoryInterface::class)
        );

        self::assertInstanceOf(
            WinnerRepositoryInterface::class,
            $this->app->make(WinnerRepositoryInterface::class)
        );
    }
}
