<?php

namespace Module\Client\Providers;

use Module\Client\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Infrastructure\Repository\OrderRepository;
use Sdk\Module\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
    }
}