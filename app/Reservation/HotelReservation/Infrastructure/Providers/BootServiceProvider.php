<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

use GTS\Reservation\HotelReservation\Domain\Adapter\FileStorageAdapterInterface;
use GTS\Reservation\HotelReservation\Infrastructure\Adapter\FileStorageAdapter;
use GTS\Reservation\HotelReservation\Infrastructure\Facade\InfoFacade;
use GTS\Reservation\HotelReservation\Infrastructure\Facade\InfoFacadeInterface;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(FileStorageAdapterInterface::class, FileStorageAdapter::class);

        $this->app->singleton(InfoFacadeInterface::class, InfoFacade::class);
    }
}
