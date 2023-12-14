<?php

namespace App\Admin\Providers;

use App\Admin\Services\JournalLogger\JournalLoggerServiceProvider;
use App\Admin\Support\Facades\AppContext;
use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Enum\SourceEnum;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AclServiceProvider::class);
        $this->app->register(FactoryServiceProvider::class);
        $this->app->register(FilemanagerServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(JournalLoggerServiceProvider::class);
        $this->app->register(HorizonServiceProvider::class);
    }

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
