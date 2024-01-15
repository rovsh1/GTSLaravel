<?php

namespace Module\Administrator\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Administrator\Domain;
use Module\Administrator\Infrastructure;

class BootServiceProvider extends ServiceProvider
{
    public $singletons = [
        Domain\Repository\AdministratorRepositoryInterface::class => Infrastructure\Repository\AdministratorRepository::class,
        Domain\Repository\BookingAdministratorRepositoryInterface::class => Infrastructure\Repository\BookingAdministratorRepository::class,
        Domain\Repository\OrderAdministratorRepositoryInterface::class => Infrastructure\Repository\OrderAdministratorRepository::class,
    ];

    public function boot()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
//        $this->app->singleton(
//            Domain\Repository\HotelRepositoryInterface::class,
//            Infrastructure\Repository\HotelRepository::class
//        );
    }
}
