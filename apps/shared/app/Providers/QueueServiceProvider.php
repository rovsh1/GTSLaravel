<?php

namespace App\Shared\Providers;

use Illuminate\Queue\QueueServiceProvider as ServiceProvider;
use Module\Support\IntegrationEventBus\Queue\Connector as IntegrationEventConnector;
use Support\MailManager\Queue\MailConnector;

class QueueServiceProvider extends ServiceProvider
{
    public function registerConnectors($manager)
    {
        parent::registerConnectors($manager);

        $manager->addConnector('mail', function () {
            return app()->make(MailConnector::class);
        });

        $manager->addConnector('integrationEvent', function () {
            $module = module('integrationEventBus');
            $module->boot();

            return $module->make(IntegrationEventConnector::class);
        });
    }
}
