<?php

namespace Module\Booking\Providers;

use Module\Booking\Domain\Invoice\Repository\ClientPaymentRepositoryInterface;
use Module\Booking\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Booking\Domain\Invoice\Service\InvoiceAmountBuilder;
use Module\Booking\Infrastructure\Repository\ClientPaymentRepository;
use Module\Booking\Infrastructure\Repository\InvoiceRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->singleton(ClientPaymentRepositoryInterface::class, ClientPaymentRepository::class);
        $this->app->singleton(InvoiceAmountBuilder::class);
    }
}
