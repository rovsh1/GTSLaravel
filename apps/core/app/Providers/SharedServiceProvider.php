<?php

namespace App\Core\Providers;

use App\Core\Support\Adapters\ConstantAdapter;
use Module\Shared\Providers\BootServiceProvider;

class SharedServiceProvider extends BootServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->app->singleton(ConstantAdapter::class);
    }
}
