<?php

namespace Module\Client\Providers;

use Module\Client\Domain;
use Module\Client\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(
            Domain\Repository\ClientRepositoryInterface::class,
            Infrastructure\Repository\ClientRepository::class
        );
        $this->app->singleton(
            Domain\Repository\LegalRepositoryInterface::class,
            Infrastructure\Repository\LegalRepository::class
        );
    }
}