<?php

namespace Module\Services\Scheduler\Providers;

use Module\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacade;
use Module\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

class BootServiceProvider extends \Sdk\Module\Foundation\Support\Providers\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CrudFacadeInterface::class, CrudFacade::class);
    }
}
