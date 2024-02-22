<?php

namespace Shared\Support\Module;

use LogicException;
use Sdk\Shared\Contracts\Context\ContextInterface;

class UseCaseWrapper
{
    public function __construct(
        private readonly ModuleRepository $modules,
        private readonly ContextInterface $appContext,
        private readonly string $useCase,
    ) {}

    public function execute(...$arguments): mixed
    {
//        if (!is_subclass_of($this->useCase, UseCaseInterface::class)) {
//            throw new \Exception('Only use case allowed');
//        }

        $module = $this->modules->findByNamespace($this->useCase);
        if (!$module) {
            throw new LogicException("Module not found by abstract [$this->useCase]");
        }
        $module->boot();

        $this->modules->callStack->push($module);
        $this->withPrevContext($module);

        $result = $module->make($this->useCase)->execute(...$arguments);
        $this->modules->callStack->pop();

        return $result;
    }

    private function withPrevContext(Module $module): void
    {
        /**
         * Передаем контекст приложения в контекст модуля
         */
        $module->get(ContextInterface::class)->setPrevContext($this->appContext->toArray());
    }
}