<?php

namespace Module\Booking\Providers\AirportBooking;

use Module\Booking\Infrastructure\AirportBooking as Infrastructure;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(
            \Module\Booking\Deprecated\AirportBooking\Repository\BookingRepositoryInterface::class,
            Infrastructure\Repository\BookingRepository::class
        );

        $this->app->singleton(
            \Module\Booking\Deprecated\AirportBooking\Repository\BookingGuestRepositoryInterface::class,
            Infrastructure\Repository\BookingGuestRepository::class
        );

        $this->app->singleton(
            \Module\Booking\Deprecated\AirportBooking\Adapter\SupplierAdapterInterface::class,
            Infrastructure\Adapter\SupplierAdapter::class
        );
    }
}
