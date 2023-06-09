<?php

namespace App\Admin\Providers;

use App\Core\Support\Facades\AppContext;
use Gsdk\Filemanager\FilemanagerServiceProvider;
use Illuminate\Support\ServiceProvider;
use Module\Shared\Enum\SourceEnum;

class BootServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(AclServiceProvider::class);
        $this->app->register(FactoryServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(FilemanagerServiceProvider::class);
        $this->app->register(AdapterServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AppContext::setSource(SourceEnum::ADMIN);
        AppContext::setRequest(request());
    }
}
