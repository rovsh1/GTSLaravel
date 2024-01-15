<?php

namespace Sdk\Module\Foundation;

use Sdk\Module\Contracts\ContextInterface;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Module\Foundation\Providers\EventServiceProvider;
use Sdk\Module\Foundation\Support\SharedContainer;
use Shared\Support\Module\Application;

class Module extends Application implements ModuleInterface, ContainerInterface
{
    public function __construct(
        private readonly string $name,
        private readonly array $config,
        private readonly SharedContainer $sharedContainer
    ) {
        parent::__construct();
        $this->instance(ModuleInterface::class, $this);
        $this->instance(ContainerInterface::class, $this);
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
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

    public function has($id): bool
    {
        return parent::has($id) || $this->sharedContainer->has($id);
    }

    protected function resolve($abstract, $parameters = [], $raiseEvents = true)
    {
        //@todo add use case wrapper and use module context context
        if (!is_string($abstract)) {
            return parent::resolve($abstract, $parameters, $raiseEvents);
        } elseif ($this->sharedContainer->has($abstract)) {
            return $this->sharedContainer->get($abstract);
        } else {
            return parent::resolve($abstract, $parameters, $raiseEvents);
        }
    }

    protected function registerBaseBindings() {}

    protected function registerBaseServiceProviders()
    {
        $this->register(EventServiceProvider::class);
    }

    public function withContext(array $context): void
    {
        $this->get(ContextInterface::class)->setPrevContext($context);
    }
}
