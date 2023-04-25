<?php

namespace App\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Services\MailManager\Infrastructure\Queue\MailConnector;

class QueueServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        //TODO
        $manager = $this->app['queue'];
        $manager->addConnector('mail', function () {
            $module = module('mail');
            $module->boot();
            return $module->make(MailConnector::class);
        });
    }
}
