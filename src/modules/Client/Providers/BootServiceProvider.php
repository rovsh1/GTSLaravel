<?php

namespace Module\Client\Providers;

use Module\Client\Domain;
use Module\Client\Infrastructure;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(PaymentServiceProvider::class);
        $this->app->register(InvoiceServiceProvider::class);
        $this->app->register(OrderServiceProvider::class);
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
