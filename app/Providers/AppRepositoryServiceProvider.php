<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class AppRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (\config('repositories') ?? [] as $interface => $concrete) {
            $this->app->bind($interface, $concrete);
        }
    }
}
