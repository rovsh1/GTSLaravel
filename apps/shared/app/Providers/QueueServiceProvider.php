<?php

namespace App\Shared\Providers;

use Illuminate\Queue\QueueServiceProvider as ServiceProvider;
use Services\IntegrationEventBus\Queue\Connector as IntegrationEventConnector;

class QueueServiceProvider extends ServiceProvider
{
    public function registerConnectors($manager)
    {
        parent::registerConnectors($manager);

        $manager->addConnector('integrationEvent', function () {
            return app()->make(IntegrationEventConnector::class);
        });
    }
}
