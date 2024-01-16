<?php

namespace Shared\Support\Module;

use Sdk\Shared\Contracts\Context\ContextInterface;

class UseCaseWrapper
{
    public function __construct(private readonly ModuleRepository $modules) {}

    public function wrap(string $abstract)
    {
        $module = $this->modules->findByNamespace($abstract);
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
//                if (!is_subclass_of($method, UseCaseInterface::class)) {
//                    throw new \Exception('Only use case allowed');
//                }

                //@todo use case middlewares?
                /**
                 * Передаем контекст приложения в контекст модуля
                 */
                $this->module->withContext(app(ContextInterface::class)->toArray());

                return $this->module->make($this->useCase)->execute(...$arguments);
            }
        };
    }
}