<?php

namespace GTS\Shared\Custom\Foundation;

class Application extends \Illuminate\Foundation\Application
{
    private ModulesRepository $modules;

    private ModuleLoader $moduleLoader;

    public function __construct($basePath = null)
    {
        $this->modules = new ModulesRepository($this);
        $this->moduleLoader = new ModuleLoader($this);

        parent::__construct($basePath);
    }

    public function module(string $name): ?Module
    {
        return $this->modules->get($name);
    }

    public function modules(): ModulesRepository
    {
        return $this->modules;
    }

    public function loadModule(string $name)
    {
        $this->modules->loadModule($name);
    }

    public function moduleLoaded(string $name): bool
    {
        return $this->modules->get($name)->isLoaded();
    }

    protected function getConcrete($abstract)
    {
        $concrete = parent::getConcrete($abstract);
        if ($concrete instanceof \Closure)
            return $concrete;

        if ($this->moduleLoader->load($abstract)) {
            return parent::getConcrete($abstract);
        } else
            return $abstract;
    }
}
