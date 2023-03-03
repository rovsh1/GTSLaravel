<?php

namespace App\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Gsdk\Filemanager\FilemanagerServiceProvider;
use App\Admin\Services\Acl\AclServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(AclServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(FilemanagerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
