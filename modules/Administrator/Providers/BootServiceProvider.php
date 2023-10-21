<?php

namespace Module\Administrator\Providers;

use Module\Administrator\Domain;
use Module\Administrator\Infrastructure;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public $singletons = [
        Domain\Repository\AdministratorRepositoryInterface::class => Infrastructure\Repository\AdministratorRepository::class,
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
