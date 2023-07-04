<?php

namespace Module\Administrator\Providers;

use Module\Administrator\Domain;
use Module\Administrator\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
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
