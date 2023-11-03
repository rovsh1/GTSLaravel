<?php

namespace Module\Supplier\Providers;

use Module\Supplier\Domain;
use Module\Supplier\Infrastructure;
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
            Domain\Supplier\Repository\CancelConditionsRepositoryInterface::class,
            Infrastructure\Repository\CancelConditionsRepository::class
        );
    }
}