<?php

namespace App\Hotel\Providers;

use App\Hotel\Services\HotelService;
use App\Hotel\Support\Facades\AppContext;
use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Enum\SourceEnum;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
    }

    public function boot(): void
    {
        AppContext::setSource(SourceEnum::HOTEL);
        $this->setRequestContext();
        $this->app->singleton(HotelService::class);
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
