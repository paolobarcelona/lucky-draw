<?php

namespace App\Providers;

use App\Services\Draw\DrawService;
use App\Services\Draw\DrawServiceInterface;
use Illuminate\Support\ServiceProvider;

final class DrawServiceProvider extends ServiceProvider
{
    /**
     * Register DrawService service.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DrawServiceInterface::class, DrawService::class);
    }
}
