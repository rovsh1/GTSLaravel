<?php

namespace Service\IntegrationEventGateway;

use Illuminate\Support\ServiceProvider;

class IntegrationEventGatewayServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(DomainEventMapper::class);
        $this->app->singleton(DomainEventGateway::class);
        $this->app->singleton(IntegrationEventDispatcher::class);
    }
}