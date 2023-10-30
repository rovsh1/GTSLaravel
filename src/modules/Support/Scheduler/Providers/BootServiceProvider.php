<?php

namespace Module\Support\Scheduler\Providers;

use Module\Support\Scheduler\Infrastructure\Facade\Cron\CrudFacade;
use Module\Support\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

class BootServiceProvider extends \Sdk\Module\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CrudFacadeInterface::class, CrudFacade::class);
    }
}
