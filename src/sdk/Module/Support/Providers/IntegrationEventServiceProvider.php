<?php

namespace Sdk\Module\Support\Providers;

use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Shared\Support\Providers\RegisterListenersTrait;

class IntegrationEventServiceProvider extends ServiceProvider
{
    use RegisterListenersTrait;

    protected array $listen = [];

    protected array $listeners = [];

    public function register(): void
    {
        $this->app->resolving(IntegrationEventSubscriberInterface::class, function ($integrationEventSubscriber) {
            $this->registerListeners($integrationEventSubscriber);
        });
    }
}
