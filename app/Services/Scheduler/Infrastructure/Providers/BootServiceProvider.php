<?php

namespace GTS\Services\Scheduler\Infrastructure\Providers;

use GTS\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacade;
use GTS\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CrudFacadeInterface::class, CrudFacade::class);
    }
}
