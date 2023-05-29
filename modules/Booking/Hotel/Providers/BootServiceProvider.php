<?php

namespace Module\Booking\Hotel\Providers;

use Module\Booking\Hotel\Domain;
use Module\Booking\Hotel\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Hotel\Infrastructure;
use Module\Booking\Hotel\Infrastructure\Adapter\FileStorageAdapter;

class BootServiceProvider extends \Sdk\Module\Foundation\Support\Providers\ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(
            FileStorageAdapterInterface::class,
            FileStorageAdapter::class
        );
        $this->app->singleton(
            Domain\Adapter\OrderAdapterInterface::class,
            Infrastructure\Adapter\OrderAdapter::class
        );
        $this->app->singleton(
            Domain\Adapter\HotelRoomAdapterInterface::class,
            Infrastructure\Adapter\HotelRoomAdapter::class
        );

        $this->app->singleton(
            Domain\Repository\BookingRepositoryInterface::class,
            Infrastructure\Repository\BookingRepository::class
        );
        $this->app->singleton(
            Domain\Repository\DetailsRepositoryInterface::class,
            Infrastructure\Repository\DetailsRepository::class
        );
//        $this->app->singleton(
//            Domain\Repository\RoomRepositoryInterface::class,
//            Infrastructure\Repository\RoomRepository::class
//        );
    }
}
