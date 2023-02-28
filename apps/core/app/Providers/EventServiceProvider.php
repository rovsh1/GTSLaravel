<?php

namespace App\Core\Providers;

use App\Core\Events\IntegrationEventHandler;
use Custom\Framework\Contracts\Event\IntegrationEventHandlerInterface;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IntegrationEventHandlerInterface::class, IntegrationEventHandler::class);
    }
}
