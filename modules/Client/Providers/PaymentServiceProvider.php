<?php

namespace Module\Client\Providers;

use Module\Client\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Domain\Payment\Repository\PlantRepositoryInterface;
use Module\Client\Infrastructure\Repository\PaymentRepository;
use Module\Client\Infrastructure\Repository\PlantRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->singleton(PlantRepositoryInterface::class, PlantRepository::class);
    }
}
