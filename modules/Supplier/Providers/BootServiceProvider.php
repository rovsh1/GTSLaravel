<?php

namespace Module\Supplier\Providers;

use Module\Supplier\Domain;
use Module\Supplier\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

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
            Infrastructure\Supplier\Repository\SupplierRepository::class
        );
    }
}
