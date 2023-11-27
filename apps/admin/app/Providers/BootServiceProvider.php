<?php

namespace App\Admin\Providers;

use App\Admin\Services\ChangeLogger\ChangeLoggerServiceProvider;
use App\Admin\Support\Facades\AppContext;
use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Enum\SourceEnum;

class BootServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(AclServiceProvider::class);
        $this->app->register(FactoryServiceProvider::class);
        $this->app->register(FilemanagerServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(ChangeLoggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AppContext::setSource(SourceEnum::ADMIN);
        $this->setRequestContext();
    }

    private function setRequestContext(): void
    {
        $request = request();
        AppContext::setHttpHost($request->getHttpHost());
        AppContext::setHttpMethod($request->getMethod());
        AppContext::setHttpUrl($request->getUri());
        AppContext::setUserIp($request->ip());
        AppContext::setUserAgent($request->userAgent());
    }
}
