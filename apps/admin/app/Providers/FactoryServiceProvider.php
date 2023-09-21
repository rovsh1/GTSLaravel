<?php

namespace App\Admin\Providers;

use App\Admin\Components\Factory\PrototypeBuilder;
use App\Admin\Components\Factory\PrototypeLoader;
use App\Admin\Components\Factory\PrototypesCollection;
use Illuminate\Support\ServiceProvider;

class FactoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('factory.prototypes', function () {
            $prototypes = new PrototypesCollection();

            (new PrototypeLoader(package_path('factories'), $prototypes))->load();

            return $prototypes;
        });

        $this->app->bind('factory.builder', fn() => new PrototypeBuilder());
    }

    public function boot() {}
}
