<?php

namespace GTS\Administrator\UI\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(ViewServiceProvider::class);
    }
}
