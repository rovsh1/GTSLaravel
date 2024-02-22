<?php

namespace Module\Client\Shared\Providers;

use Module\Client\Moderation\Domain;
use Module\Client\Moderation\Infrastructure;
use Module\Client\Shared\Domain\Repository\ClientRepositoryInterface;
use Module\Client\Shared\Domain\Repository\ClientRequisitesRepositoryInterface;
use Module\Client\Shared\Infrastructure\Repository\ClientRepository;
use Module\Client\Shared\Infrastructure\Repository\ClientRequisitesRepository;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(
            ClientRepositoryInterface::class,
            ClientRepository::class
        );
        $this->app->singleton(
            ClientRequisitesRepositoryInterface::class,
            ClientRequisitesRepository::class
        );
    }
}
