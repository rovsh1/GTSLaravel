<?php

namespace App\Core\Providers;

use App\Core\Events\IntegrationEventHandler;
use Sdk\Module\Contracts\Event\IntegrationEventHandlerInterface;

class EventServiceProvider extends \Sdk\Module\Foundation\Support\Providers\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IntegrationEventHandlerInterface::class, IntegrationEventHandler::class);
    }
}
