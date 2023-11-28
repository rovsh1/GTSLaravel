<?php

namespace Module\Client\Payment\Providers;

use Module\Client\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Payment\Infrastructure\Repository\PaymentRepository;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(PaymentRepositoryInterface::class, PaymentRepository::class);
    }
}
