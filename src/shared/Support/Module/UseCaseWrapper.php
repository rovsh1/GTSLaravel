<?php

namespace Shared\Support\Module;

class UseCaseWrapper
{
    public function __construct(private readonly ModuleRepository $modules) {}

    public function wrap(string $abstract)
    {
        $module = $this->findByNamespace($abstract);
        if (!$module) {
            throw new \LogicException("Module not found by abstract [$abstract]");
        }
        $module->boot();

        return new class($module, $abstract) {
            public function __construct(
                private readonly Module $module,
                private readonly string $useCase,
            ) {}

            public function execute(...$arguments): mixed
            {
                return $this->module->callUseCase($this->useCase, $arguments);
            }
        };
    }

    private function findByNamespace(string $abstract): ?Module
    {
        foreach ($this->modules as $module) {
            if ($module->hasSubclass($abstract)) {
                return $module;
            }
        }

        return null;
    }
}