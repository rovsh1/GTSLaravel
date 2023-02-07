<?php

namespace GTS\Shared\UI\Api\Providers;

use GTS\Shared\UI\Common\Support\BootServiceProvider as ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerModulesUI('Api');
    }
}
