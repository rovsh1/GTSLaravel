<?php

declare(strict_types=1);

namespace App\Shared\Logging\Processor;

use Illuminate\Contracts\Container\Container;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Sdk\Shared\Contracts\Context\ContextInterface;

class ContextProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Container $container
    ) {}

    /**
     * @inheritDoc
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        if ($this->container->has(ContextInterface::class)) {
            $record->extra['flow'] = $this->container->get(ContextInterface::class)->toArray();
        }

        return $record;
    }
}
