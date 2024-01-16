<?php

namespace Module\Client\Invoicing\Providers;

use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Infrastructure\Repository\InvoiceRepository;
use Illuminate\Support\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(InvoiceRepositoryInterface::class, InvoiceRepository::class);
    }
}
