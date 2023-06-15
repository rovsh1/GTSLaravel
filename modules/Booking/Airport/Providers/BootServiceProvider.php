<?php

namespace Module\Booking\Airport\Providers;

use Module\Booking\Hotel\Domain;
use Module\Booking\Hotel\Infrastructure;
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
