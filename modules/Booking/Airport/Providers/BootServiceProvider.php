<?php

namespace Module\Booking\Airport\Providers;

use Module\Booking\Airport\Domain;
use Module\Booking\Airport\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(
            Domain\Repository\BookingRepositoryInterface::class,
            Infrastructure\Repository\BookingRepository::class
        );

        $this->app->singleton(
            Domain\Repository\BookingGuestRepositoryInterface::class,
            Infrastructure\Repository\BookingGuestRepository::class
        );

        $this->app->singleton(
            Domain\Repository\CancelConditionsRepositoryInterface::class,
            Infrastructure\Repository\CancelConditionsRepository::class
        );

        $this->app->singleton(
            Domain\Adapter\SupplierAdapterInterface::class,
            Infrastructure\Adapter\SupplierAdapter::class
        );
    }
}
