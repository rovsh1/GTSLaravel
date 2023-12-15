<?php

namespace Module\Client\Moderation\Providers;

use Module\Client\Moderation\Domain;
use Module\Client\Moderation\Infrastructure;
use Sdk\Module\Support\ServiceProvider;

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
            Domain\Repository\ClientRepositoryInterface::class,
            Infrastructure\Repository\ClientRepository::class
        );
        $this->app->singleton(
            Domain\Repository\LegalRepositoryInterface::class,
            Infrastructure\Repository\LegalRepository::class
        );
        $this->app->singleton(
            Domain\Repository\CurrencyRateRepositoryInterface::class,
            Infrastructure\Repository\CurrencyRateRepository::class
        );
        $this->app->singleton(
            Domain\Repository\ClientRequisitesRepositoryInterface::class,
            Infrastructure\Repository\ClientRequisitesRepository::class
        );
        $this->app->singleton(
            Domain\Repository\ContractRepositoryInterface::class,
            Infrastructure\Repository\ContractRepository::class
        );
    }
}
