<?php

namespace App\Admin\Providers;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Components\Factory\ConfigLoader;
use App\Admin\Components\Factory\FactoryManager;
use Illuminate\Support\ServiceProvider;

class FactoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('factory', function () {
            $factory = new FactoryManager();
            $prototypes = $factory->prototypes();
            $factoryLoader = new ConfigLoader(resource_path('factory/configs'));
            foreach ($factoryLoader->load() as $config) {
                $prototypes->add(new Prototype($config));
            }
            return $factory;
        });

        $this->app->bind('factory.prototypes', fn() => app('factory')->prototypes());
    }
}
