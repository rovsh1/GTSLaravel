<?php

namespace Module\Services\Scheduler\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacade;
use Module\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CrudFacadeInterface::class, CrudFacade::class);
    }
}
