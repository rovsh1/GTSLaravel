<?php

namespace App\Shared\Support\Module\Monolith;

use Illuminate\Contracts\Foundation\Application;
use Sdk\Module\Foundation\Support\SharedContainer;

class SharedKernel
{
    protected Application $app;

    protected SharedContainer $sharedContainer;

    protected array $serviceProviders = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->sharedContainer = $this->makeSharedContainer();

        $this->registerServiceProviders();
    }

    public function register($provider): void
    {
        $resolved = new $provider($this);

        if (method_exists($resolved, 'register')) {
            $resolved->register();
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

    public function getContainer(): SharedContainer
    {
        return $this->sharedContainer;
    }

    public function resolving(string $abstract, \Closure $bind): void
    {
        $this->sharedContainer->resolving($abstract, $bind);
    }

    protected function makeSharedContainer(): SharedContainer
    {
        return new SharedContainer();
    }

    protected function registerServiceProviders(): void {}
}
