<?php

namespace Sdk\Module\Support\Providers;

use Illuminate\Support\ServiceProvider;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Shared\Support\Providers\RegisterListenersTrait;

class DomainEventServiceProvider extends ServiceProvider
{
    use RegisterListenersTrait;

    protected array $listen = [];

    protected array $listeners = [];

    public function register(): void
    {
        $this->app->resolving(DomainEventDispatcherInterface::class, function ($domainEventDispatcher) {
            $this->registerListeners($domainEventDispatcher);
        });
    }
}
