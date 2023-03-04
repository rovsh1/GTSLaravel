<?php

namespace App\Admin\Components\Resource;

use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('resources', function () {
            $resources = new ResourceManager();
            $resourceLoader = new ResourceLoader(resource_path('models'));
            foreach ($resourceLoader->loadResources() as $resource) {
                $resources->addResource($resource);
            }
            return $resources;
        });
    }
}
