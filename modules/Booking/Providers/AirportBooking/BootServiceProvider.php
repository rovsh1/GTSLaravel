<?php

namespace Module\Booking\Providers\AirportBooking;

use Module\Booking\Airport\Domain;
use Module\Booking\Airport\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(
            Domain\Booking\Repository\BookingRepositoryInterface::class,
            Infrastructure\Repository\BookingRepository::class
        );

        $this->app->singleton(
            Domain\Booking\Repository\BookingGuestRepositoryInterface::class,
            Infrastructure\Repository\BookingGuestRepository::class
        );

        $this->app->singleton(
            Domain\Booking\Adapter\SupplierAdapterInterface::class,
            Infrastructure\Adapter\SupplierAdapter::class
        );
    }
}
