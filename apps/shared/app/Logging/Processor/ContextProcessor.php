<?php

declare(strict_types=1);

namespace App\Shared\Logging\Processor;

use Illuminate\Contracts\Container\Container;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Shared\Support\Module\ModuleRepository;

class ContextProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ModuleRepository $modules,
        private readonly Container $appContainer
    ) {}

    /**
     * @inheritDoc
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $container = $this->modules->callStack->isEmpty()
            ? $this->appContainer
            : $this->modules->callStack->top();
        if ($container->has(ContextInterface::class)) {
            $record->extra['flow'] = $container->get(ContextInterface::class)->toArray();
        }

        return $record;
    }
}
