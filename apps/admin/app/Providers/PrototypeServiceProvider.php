<?php

namespace App\Admin\Providers;

use App\Admin\Components\Prototype\Prototype;
use App\Admin\Components\Prototype\PrototypeLoader;
use App\Admin\Components\Prototype\PrototypeManager;
use Illuminate\Support\ServiceProvider;

class PrototypeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('prototypes', function () {
            $prototypes = new PrototypeManager();
            $prototypeLoader = new PrototypeLoader(resource_path('prototypes'));
            foreach ($prototypeLoader->load() as $config) {
                $prototypes->add(new Prototype($config));
            }
            return $prototypes;
        });
    }
}
