<?php

namespace Module\Pricing\Providers;

use Module\Pricing\Domain\Markup\Service\HotelMarkupFinderInterface;
use Module\Pricing\Infrastructure\Service\HotelMarkupFinder;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class MarkupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(HotelMarkupFinderInterface::class, HotelMarkupFinder::class);
    }
}
