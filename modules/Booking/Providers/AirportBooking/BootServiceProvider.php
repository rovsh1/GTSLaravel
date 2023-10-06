<?php

namespace Module\Booking\Providers\AirportBooking;

use Module\Booking\Domain\AirportBooking as Domain;
use Module\Booking\Infrastructure\AirportBooking as Infrastructure;
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
            Domain\Adapter\SupplierAdapterInterface::class,
            Infrastructure\Adapter\SupplierAdapter::class
        );
    }
}
