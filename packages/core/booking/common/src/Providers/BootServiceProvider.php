<?php

namespace Pkg\Booking\Common\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerMigrations();
        $this->app->register(SharedBookingServiceProvider::class);
    }

    protected function registerMigrations(): void
    {
        if ($this->app->runningInConsole()) {
//            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }
}
