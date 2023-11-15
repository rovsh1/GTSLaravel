<?php

namespace Module\Supplier\Moderation\Providers;

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
            \Module\Supplier\Moderation\Domain\Supplier\Repository\SupplierRepositoryInterface::class,
            \Module\Supplier\Moderation\Infrastructure\Repository\SupplierRepository::class
        );

        $this->app->singleton(
            \Module\Supplier\Moderation\Domain\Supplier\Repository\ContractRepositoryInterface::class,
            \Module\Supplier\Moderation\Infrastructure\Repository\ContractRepository::class
        );

        $this->app->singleton(
            \Module\Supplier\Moderation\Domain\Supplier\Repository\CancelConditionsRepositoryInterface::class,
            \Module\Supplier\Moderation\Infrastructure\Repository\CancelConditionsRepository::class
        );
    }
}
