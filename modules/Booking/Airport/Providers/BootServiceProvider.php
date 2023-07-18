<?php

namespace Module\Booking\Airport\Providers;

use Module\Booking\Airport\Domain;
use Module\Booking\Airport\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            Domain\Repository\BookingRepositoryInterface::class,
            Infrastructure\Repository\BookingRepository::class
        );
    }
}
