<?php

namespace Module\Client\Providers;

use Module\Client\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Infrastructure\Repository\InvoiceRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(InvoiceRepositoryInterface::class, InvoiceRepository::class);
    }
}