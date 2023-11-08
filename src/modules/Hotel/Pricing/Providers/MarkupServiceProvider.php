<?php

namespace Module\Hotel\Pricing\Providers;

use Module\Hotel\Pricing\Domain\Markup\Service\HotelMarkupFinderInterface;
use Module\Hotel\Pricing\Infrastructure\Service\HotelMarkupFinder;
use Sdk\Module\Support\ServiceProvider;

class MarkupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(HotelMarkupFinderInterface::class, HotelMarkupFinder::class);
    }
}
