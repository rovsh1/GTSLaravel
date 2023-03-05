<?php

namespace App\Admin\Providers;

use App\Admin\Components\Acl\AclServiceProvider;
use App\Admin\Components\Source\SourceServiceProvider;
use Gsdk\Filemanager\FilemanagerServiceProvider;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(SourceServiceProvider::class);
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
