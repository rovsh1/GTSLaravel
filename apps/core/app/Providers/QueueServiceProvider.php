<?php

namespace App\Core\Providers;

use Illuminate\Queue\QueueServiceProvider as ServiceProvider;
use Module\Support\IntegrationEventBus\Queue\Connector as IntegrationEventConnector;
use Module\Support\MailManager\Infrastructure\Queue\MailConnector;

class QueueServiceProvider extends ServiceProvider
{
    public function registerConnectors($manager)
    {
        parent::registerConnectors($manager);

        $manager->addConnector('mail', function () {
            $module = module('mail');
            $module->boot();

            return $module->make(MailConnector::class);
        });

        $manager->addConnector('integrationEvent', function () {
            $module = module('integrationEventBus');
            $module->boot();

            return $module->make(IntegrationEventConnector::class);
        });
    }
}
