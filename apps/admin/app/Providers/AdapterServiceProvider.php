<?php

namespace App\Admin\Providers;

use App\Admin\Support\Adapters\Booking;
use App\Admin\Support\Adapters\Hotel;
use App\Admin\Support\Adapters\MailAdapter;
use Illuminate\Support\ServiceProvider;

class AdapterServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->app->singleton('mail-adapter', MailAdapter::class);
        $this->app->singleton(Hotel\QuotaAdapter::class, Hotel\QuotaAdapter::class);
        $this->app->singleton(Hotel\MarkupSettingsAdapter::class, Hotel\MarkupSettingsAdapter::class);
        $this->app->singleton(Hotel\PricesAdapter::class, Hotel\PricesAdapter::class);
        $this->app->singleton(Booking\HotelAdapter::class, Booking\HotelAdapter::class);
        $this->app->singleton(Booking\OrderAdapter::class, Booking\OrderAdapter::class);
    }
}
