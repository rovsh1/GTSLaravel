<?php

namespace Module\Booking\Transfer\Providers;


use Module\Booking\Transfer\Domain;
use Module\Booking\Transfer\Infrastructure;
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
            Domain\Booking\Adapter\SupplierAdapterInterface::class,
            Infrastructure\Adapter\SupplierAdapter::class
        );
    }
}
