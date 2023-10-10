<?php

namespace Sdk\Module\Foundation;

use Illuminate\Contracts\Foundation\Application;
use Sdk\Module\Foundation\Support\SharedContainer;

class SharedKernel
{
    protected Application $app;

    protected SharedContainer $sharedContainer;

    protected array $serviceProviders = [];

    public function __construct(Application $app,)
    {
        $this->app = $app;
        $this->sharedContainer = $this->makeSharedContainer();

        $this->registerServiceProviders();
        $this->registerRequiredDependencies();
    }

    public function register($provider): void
    {
        $resolved = new $provider($this);

        if (method_exists($resolved, 'register')) {
            $resolved->register();
        }

        if (property_exists($provider, 'singletons')) {
            foreach ($provider->singletons as $key => $value) {
                $key = is_int($key) ? $value : $key;

                $this->singleton($key, $value);
            }
        }

        $this->serviceProviders[] = $resolved;
    }

    public function boot(): void
    {
        foreach ($this->serviceProviders as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }
        unset($this->serviceProviders);
    }

    public function singleton($abstract, $concrete = null): void
    {
        $this->sharedContainer->singleton($abstract, $concrete);
        $this->app->bind($abstract, fn() => $this->sharedContainer->get($abstract));
    }

    public function getContainer(): SharedContainer
    {
        return $this->sharedContainer;
    }

    protected function makeSharedContainer(): SharedContainer
    {
        return new SharedContainer();
    }

    protected function registerRequiredDependencies(): void
    {

    }

    protected function registerServiceProviders(): void
    {
    }
}
