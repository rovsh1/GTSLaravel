<?php

namespace App\Core\Providers;

use Illuminate\Queue\QueueServiceProvider as ServiceProvider;
use Module\Support\MailManager\Infrastructure\Queue\MailConnector;

class QueueServiceProvider extends ServiceProvider
{
    public function registerConnectors($manager)
    {
        parent::registerConnectors($manager);

        $manager->addConnector('Mail', function () {
            $module = module('mail');
            $module->boot();
            return $module->make(MailConnector::class);
        });
    }
}
