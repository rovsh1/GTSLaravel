<?php

namespace Module\Client\Invoicing\Providers;

use Illuminate\Support\Facades\View;
use Module\Client\Invoicing\Domain;
use Module\Client\Invoicing\Infrastructure;
use Module\Shared\Service\TemplateCompilerInterface;
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
        View::addLocation(base_path('resources/pdf-templates'));

        $this->app->singleton(TemplateCompilerInterface::class, Infrastructure\Service\InvoiceTemplateCompiler::class);
    }

}
