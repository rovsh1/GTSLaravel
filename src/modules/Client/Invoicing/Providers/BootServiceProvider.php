<?php

namespace Module\Client\Invoicing\Providers;

use Module\Client\Invoicing\Domain;
use Module\Client\Invoicing\Infrastructure;
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

    private function registerInterfaces() {}
}
