<?php

namespace Module\Booking\Providers;

use Module\Booking\Airport\Providers\BootServiceProvider as AirportBootProvider;
use Module\Booking\Common\Domain;
use Module\Booking\Common\Infrastructure;
use Module\Booking\Hotel\Providers\BootServiceProvider as HotelBootProvider;
use Module\Booking\Order\Providers\BootServiceProvider as OrderBootProvider;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public $singletons = [
        Domain\Repository\RequestRepositoryInterface::class => Infrastructure\Repository\RequestRepository::class,
        Domain\Repository\BookingChangesLogRepositoryInterface::class => Infrastructure\Repository\BookingChangesLogRepository::class,
        Domain\Adapter\FileStorageAdapterInterface::class => Infrastructure\Adapter\FileStorageAdapter::class,
    ];

    public function register()
    {
        $this->app->register(OrderBootProvider::class);
        $this->app->register(HotelBootProvider::class);
        $this->app->register(AirportBootProvider::class);
    }
}
