<?php

namespace Gsdk\Meta;

use Illuminate\Support\ServiceProvider;

class MetaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('meta', MetaTags::class);
    }
}
