<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Providers;

use GTS\Reservation\HotelReservation\Domain\Adapter\FileStorageAdapterInterface;
use GTS\Reservation\HotelReservation\Infrastructure\Adapter\FileStorageAdapter;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(FileStorageAdapterInterface::class, FileStorageAdapter::class);
    }
}
