<?php

namespace Sdk\Module\Foundation;

use Illuminate\Support\ServiceProvider;
use Sdk\Module\Container\Container;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Module\Foundation\Providers\BusServiceProvider;
use Sdk\Module\Foundation\Providers\EventServiceProvider;
use Sdk\Module\Foundation\Support\SharedContainer;

class Module extends Container implements ModuleInterface, ContainerInterface
{
    private bool $booted = false;

    protected array $serviceProviders = [];

    protected array $loadedProviders = [];

    public function __construct(
        private readonly string $name,
        private readonly array $config,
        private readonly SharedContainer $sharedContainer
    ) {
        $this->instance(ModuleInterface::class, $this);
        $this->instance(ContainerInterface::class, $this);
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
        $this->register($this->namespace('Providers\BootServiceProvider'));
    }

    public function config(string $name = null)
    {
        if (null === $name) {
            return $this->config;
        }

        $tmp = $this->config;
        foreach (explode('.', $name) as $k) {
            if (!isset($tmp[$k])) {
                return null;
            }

            $tmp = $tmp[$k];
        }

        return $tmp;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function is(string|Module $name): bool
    {
        if ($name instanceof Module) {
            return $this->is($name->name());
        }

        $name = strtolower($name);

        return strtolower($this->name) === $name || $this->config('alias') === $name;
    }

    public function hasSubclass(string $abstract): bool
    {
        return str_starts_with($abstract, $this->namespace());
    }

    public function path(string $path = null): string
    {
        return $this->config('path') . ($path ? DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR) : '');
    }

    public function namespace(string $namespace = null): string
    {
        return $this->config('namespace') . '\\' . trim($namespace, '\\');
    }

    public function register($provider, $force = false)
    {
        if (($registered = $this->getProvider($provider)) && !$force) {
            return $registered;
        }

        // If the given "provider" is a string, we will resolve it, passing in the
        // application instance automatically for the developer. This is simply
        // a more convenient way of specifying your service provider classes.
        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        $provider->register();

        // If there are bindings / singletons set as properties on the provider we
        // will spin through them and register them with the application, which
        // serves as a convenience layer while registering a lot of bindings.
        if (property_exists($provider, 'bindings')) {
            foreach ($provider->bindings as $key => $value) {
                $this->bind($key, $value);
            }
        }

        if (property_exists($provider, 'singletons')) {
            foreach ($provider->singletons as $key => $value) {
                $key = is_int($key) ? $value : $key;

                $this->singleton($key, $value);
            }
        }

        $this->markAsRegistered($provider);

        // If the application has already booted, we will call this boot method on
        // the provider class so it has an opportunity to do its boot logic and
        // will be ready for any usage by this developer's application logic.
        if ($this->isBooted()) {
            $this->bootProvider($provider);
        }

        return $provider;
    }

    public function getProvider($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);
        foreach ($this->serviceProviders as $serviceProvider) {
            if ($serviceProvider instanceof $name) {
                return $serviceProvider;
            }
        }

        return null;
    }

    public function resolveProvider($provider)
    {
        return new $provider($this);
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }

    public function boot()
    {
        if ($this->isBooted()) {
            return;
        }

        // Once the application has booted we will also fire some "booted" callbacks
        // for any listeners that need to do work after this initial booting gets
        // finished. This is useful when ordering the boot-up processes we run.
//        $this->fireAppCallbacks($this->bootingCallbacks);

        array_walk($this->serviceProviders, function ($p) {
            $this->bootProvider($p);
        });

        $this->booted = true;
//        $this->fireAppCallbacks($this->bootedCallbacks);
    }

    public function has($id): bool
    {
        return parent::has($id) || $this->sharedContainer->has($id);
    }

    protected function resolve($abstract, $parameters = [], $raiseEvents = true)
    {
        if (!is_string($abstract)) {
            return parent::resolve($abstract, $parameters, $raiseEvents);
        } elseif ($this->sharedContainer->has($abstract)) {
            return $this->sharedContainer->get($abstract);
        } else {
            return parent::resolve($abstract, $parameters, $raiseEvents);
        }
    }

    protected function registerBaseBindings()
    {
    }

    protected function registerBaseServiceProviders()
    {
        $this->register(BusServiceProvider::class);
        $this->register(EventServiceProvider::class);
    }

    protected function bootProvider(ServiceProvider $provider)
    {
        $provider->callBootingCallbacks();

        if (method_exists($provider, 'boot')) {
            $this->call([$provider, 'boot']);
        }

        $provider->callBootedCallbacks();
    }

    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }
}
