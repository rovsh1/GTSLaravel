<?php

namespace Pkg\IntegrationEventBus;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;
use Pkg\IntegrationEventBus\Queue\Connector as IntegrationEventConnector;
use Pkg\IntegrationEventBus\Service\MessageSender;

class IntegrationEventBusServiceProvider extends ServiceProvider
{
    private array $availableModules = [
        'BookingEventSourcing',
        'BookingRequesting',
        'ClientPayment',
        'SupplierPayment',
    ];

    public function register(): void
    {
        $this->registerManager();
    }

    public function boot(): void
    {
        $this->app->singleton(MessageSender::class, fn() => new MessageSender($this->availableModules));
    }

    protected function registerManager(): void
    {
        $this->callAfterResolving(QueueManager::class, function ($manager) {
            $manager->addConnector('integrationEvent', function () {
                return app()->make(IntegrationEventConnector::class);
            });
        });
    }
}
