<?php

namespace GTS\Shared\UI\Admin\Providers;

use GTS\Shared\UI\Common\Support\BootServiceProvider as ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);

        $this->registerModulesUI('Admin');
    }
}
