<?php

namespace App\Admin\Providers;

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
    }
}
