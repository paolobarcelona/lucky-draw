<?php

namespace Tests\Unit\Providers;

use App\Services\Draw\DrawServiceInterface;
use Tests\AbstractTestCase;

/**
 * @covers \App\Providers\DrawServiceProvider
 */
final class DrawServiceProviderTest extends AbstractTestCase
{
    /**
     * Should Register all services into the container.
     *
     * @return void
     */
    public function testRegister(): void
    {
        self::assertInstanceOf(
            DrawServiceInterface::class,
            $this->app->make(DrawServiceInterface::class)
        );
    }
}
