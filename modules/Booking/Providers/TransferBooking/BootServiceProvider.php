<?php

namespace Module\Booking\Providers\TransferBooking;

use Module\Booking\Domain\TransferBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Infrastructure\TransferBooking\Adapter\SupplierAdapter;
use Module\Booking\Infrastructure\TransferBooking\Repository\BookingRepository;
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
            SupplierAdapterInterface::class,
            SupplierAdapter::class
        );
    }
}