<?php

namespace Module\Supplier\Moderation\Providers;

use Module\Supplier\Moderation\Domain;
use Module\Supplier\Moderation\Infrastructure;
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
            Domain\Supplier\Repository\SupplierRepositoryInterface::class,
            Infrastructure\Repository\SupplierRepository::class
        );

        $this->app->singleton(
            Domain\Supplier\Repository\ContractRepositoryInterface::class,
            Infrastructure\Repository\ContractRepository::class
        );

        $this->app->singleton(
            Domain\Supplier\Repository\TransferCancelConditionsRepositoryInterface::class,
            Infrastructure\Repository\TransferCancelConditionsRepository::class
        );

        $this->app->singleton(
            Domain\Supplier\Repository\AirportCancelConditionsRepositoryInterface::class,
            Infrastructure\Repository\AirportCancelConditionsRepository::class
        );
    }
}
