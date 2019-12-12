<?php

namespace Tests\Unit\Providers;

use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
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
    }
}
