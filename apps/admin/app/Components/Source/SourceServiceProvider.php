<?php

namespace App\Admin\Components\Source;

use Illuminate\Support\ServiceProvider;

class SourceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('resources', function () {
            $resources = new SourceManager();
            $resourceLoader = new SourceLoader(resource_path('sources'));
            foreach ($resourceLoader->loadResources() as $resource) {
                $resources->addResource($resource);
            }
            return $resources;
        });
    }
}
