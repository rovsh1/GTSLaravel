<?php

namespace Shared\Support\Module;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Sdk\Module\Contracts\ContextInterface as ModuleContextInterface;
use Sdk\Module\Contracts\LoggerInterface;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Module\Foundation\Providers\EventServiceProvider;
use Sdk\Module\Logging\Logger;
use Sdk\Module\Support\Context\ModuleContext;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Shared\Event\IntegrationEventMessage;

class Module extends Container implements ModuleInterface, ContainerInterface, CachesConfiguration, CachesRoutes
{
    use ApplicationTrait;
    use HackBindingsTrait;

    public function __construct(
        private readonly string $name,
        private readonly string $namespace,
        private readonly SharedContainer $sharedContainer,
        private readonly array $config
    ) {
        $this->registerHackBindings();
        $this->registerBaseProviders();
        $this->registerBaseBindings();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function is(string|ModuleInterface $name): bool
    {
        if ($name instanceof ModuleInterface) {
            return $this === $name;
        }

        $name = strtolower($name);

        return strtolower($this->name) === $name || $this->config('alias') === $name;
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

    public function hasSubclass(string $abstract): bool
    {
        return str_starts_with($abstract, $this->namespace());
    }

    public function namespace(string $namespace = null): string
    {
        return $this->namespace . ($namespace ? trim($namespace, '\\') . '\\' : '');
    }

    public function has($id): bool
    {
        return parent::has($id) || $this->sharedContainer->has($id);
    }

    public function registerBaseProviders(): void
    {
        $this->register(EventServiceProvider::class);
        $bootServiceProvider = "{$this->namespace}Providers\\BootServiceProvider";
        if (class_exists($bootServiceProvider)) {
            $this->register($bootServiceProvider);
        }
        $bootServiceProvider = "{$this->namespace}{$this->name}ServiceProvider";
        if (class_exists($bootServiceProvider)) {
            $this->register($bootServiceProvider);
        }
    }

    public function registerBaseBindings(): void
    {
        $this->bind(ModuleInterface::class, fn() => $this);
        $this->bind(ContainerInterface::class, fn() => $this);
        $this->singleton(ModuleContextInterface::class, ModuleContext::class);
        $this->alias(ModuleContextInterface::class, ContextInterface::class);
        $this->singleton(LoggerInterface::class, Logger::class);
    }

    public function dispatchEvent(IntegrationEventMessage $message): void
    {
        $this->get(IntegrationEventSubscriberInterface::class)->handle($message);
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
}