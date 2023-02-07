<?php

namespace GTS\Services\Scheduler\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\ServiceProvider;
use GTS\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacade;
use GTS\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CrudFacadeInterface::class, CrudFacade::class);
    }
}
