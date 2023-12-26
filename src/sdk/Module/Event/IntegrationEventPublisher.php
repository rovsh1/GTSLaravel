<?php

namespace Sdk\Module\Event;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Sdk\Module\Contracts\ContextInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventPublisherInterface;
use Sdk\Module\Contracts\ModuleInterface;

class IntegrationEventPublisher implements IntegrationEventPublisherInterface
{
    private const QUEUE = 'integration_events';

    private Connection $connection;

    public function __construct(
        private readonly ModuleInterface $module,
        private readonly ContextInterface $context
    ) {}

    public function publish(IntegrationEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->connection()->rPush(
                self::QUEUE,
                json_encode([
                    'id' => Str::orderedUuid()->toString(),
                    'module' => $this->module->name(),
                    'status' => 0,
                    'event' => $event::class,
                    'payload' => serialize($event),
                    'context' => $this->context->toArray(),
                    'timestamp' => time()
                ])
            );
        }
    }

    private function connection(): Connection
    {
        return $this->connection
            ?? ($this->connection = Redis::connection('events'));
    }
}