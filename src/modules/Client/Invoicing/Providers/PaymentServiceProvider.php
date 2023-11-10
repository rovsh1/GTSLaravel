<?php

namespace Module\Client\Invoicing\Providers;

use Module\Client\Invoicing\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Invoicing\Domain\Payment\Repository\PlantRepositoryInterface;
use Module\Client\Invoicing\Infrastructure\Repository\PaymentRepository;
use Module\Client\Invoicing\Infrastructure\Repository\PlantRepository;
use Sdk\Module\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->singleton(PlantRepositoryInterface::class, PlantRepository::class);
    }
}
