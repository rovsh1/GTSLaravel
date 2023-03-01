<?php

namespace Module\Reservation\HotelReservation\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Reservation\HotelReservation\Domain;
use Module\Reservation\HotelReservation\Domain\Adapter\FileStorageAdapterInterface;
use Module\Reservation\HotelReservation\Infrastructure;
use Module\Reservation\HotelReservation\Infrastructure\Adapter\FileStorageAdapter;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(FileStorageAdapterInterface::class, FileStorageAdapter::class);

        $this->app->singleton(Domain\Repository\ReservationRepositoryInterface::class, Infrastructure\Repository\ReservationRepository::class);
        $this->app->singleton(Domain\Repository\RoomRepositoryInterface::class, Infrastructure\Repository\RoomRepository::class);
    }
}
