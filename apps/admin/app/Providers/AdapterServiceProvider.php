<?php

namespace App\Admin\Providers;

use App\Admin\Support\Adapters\Hotel\QuotaAdapter;
use App\Admin\Support\Adapters\Hotel\MarkupSettingsAdapter;
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
        $this->app->singleton(QuotaAdapter::class, QuotaAdapter::class);
        $this->app->singleton(MarkupSettingsAdapter::class, MarkupSettingsAdapter::class);
    }
}
