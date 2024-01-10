<?php

namespace Module\Client\Invoicing\Providers;

use Module\Client\Invoicing\Domain;
use Module\Client\Invoicing\Infrastructure;
use Module\Client\Shared\Providers\BootServiceProvider as SharedClientServiceProvider;
use Sdk\Module\Support\ServiceProvider;


class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(SharedClientServiceProvider::class);
        $this->app->register(InvoiceServiceProvider::class);
        $this->app->register(OrderServiceProvider::class);
    }

    public function boot()
    {
        $this->app->singleton(
            Domain\Invoice\Adapter\FileGeneratorAdapterInterface::class,
            Infrastructure\Adapter\FileGeneratorAdapter::class
        );
        $this->app->singleton(
            Domain\Invoice\Adapter\MailGeneratorAdapterInterface::class,
            Infrastructure\Adapter\MailGeneratorAdapter::class
        );
    }
}
