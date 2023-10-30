<?php

namespace App\Shared\Support\Module\Monolith;

use App\Shared\Support\Module\ModulesManager;

class UseCaseWrapper
{
    public function __construct(private readonly ModulesManager $modules)
    {
    }

    public function wrap(string $abstract)
    {
        $moduleAdapter = $this->findByNamespace($abstract);
        if (!$moduleAdapter) {
            throw new \LogicException("Module not found by abstract [$abstract]");
        }
        $moduleAdapter->boot();

        return new class($moduleAdapter, $abstract) {
            public function __construct(
                private readonly ModuleAdapter $moduleAdapter,
                private readonly string $useCase,
            ) {
            }

            public function execute(...$arguments): mixed
            {
                return $this->moduleAdapter->call($this->useCase, $arguments);
            }
        };
    }

    private function findByNamespace(string $abstract): ?ModuleAdapter
    {
        foreach ($this->modules as $module) {
            if ($module->hasSubclass($abstract)) {
                return $module;
            }
        }

        return null;
    }
}