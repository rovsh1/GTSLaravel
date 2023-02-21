<?php

namespace Module\Shared\UI\Api\Providers;

use Module\Shared\UI\Common\Support\BootServiceProvider as ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerModulesUI('Api');
    }
}
