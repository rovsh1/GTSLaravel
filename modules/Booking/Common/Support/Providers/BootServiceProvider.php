<?php

namespace Module\Booking\Common\Support\Providers;

use Module\Booking\Common\Domain;
use Module\Booking\Common\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            Domain\Repository\BookingRepositoryInterface::class,
            Infrastructure\Repository\BookingRepository::class
        );
        $this->app->singleton(
            Domain\Adapter\OrderAdapterInterface::class,
            Infrastructure\Adapter\OrderAdapter::class
        );
    }
}
