<?php

namespace Module\Booking\Providers\ServiceBooking;

use Module\Booking\Domain\ServiceBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\Repository\DetailsRepositoryInterface;
use Module\Booking\Infrastructure\ServiceBooking\Adapter\SupplierAdapter;
use Module\Booking\Infrastructure\ServiceBooking\Repository\BookingRepository;
use Module\Booking\Infrastructure\ServiceBooking\Repository\DetailsRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(
            BookingRepositoryInterface::class,
            BookingRepository::class
        );

        $this->app->singleton(
            DetailsRepositoryInterface::class,
            DetailsRepository::class
        );

        $this->app->singleton(
            SupplierAdapterInterface::class,
            SupplierAdapter::class
        );
    }
}
