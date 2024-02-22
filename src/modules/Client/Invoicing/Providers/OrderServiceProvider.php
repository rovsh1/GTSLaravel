<?php

namespace Module\Client\Invoicing\Providers;

use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Invoicing\Infrastructure\Repository\OrderRepository;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
    }
}
