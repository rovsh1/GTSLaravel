<?php

namespace App\Core\Providers;

use Module\Services\MailManager\Infrastructure\Queue\MailConnector;
use Illuminate\Queue\QueueServiceProvider as ServiceProvider;

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
    }
}
