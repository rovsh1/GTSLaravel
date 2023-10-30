<?php

namespace Sdk\Module\Event;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventPublisherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMapperInterface;
use Sdk\Module\Contracts\ModuleInterface;

class DomainEventPublisher implements DomainEventPublisherInterface
{
    private const QUEUE = 'integration_events';

    private IntegrationEventMapperInterface $integrationEventMapper;

    private Connection $connection;

    public function __construct(private readonly ModuleInterface $module)
    {
    }

    public function registerMapper(IntegrationEventMapperInterface $integrationEventMapper): void
    {
        $this->integrationEventMapper = $integrationEventMapper;
    }

    public function publish(DomainEventInterface ...$events): void
    {
        if (!isset($this->integrationEventMapper)) {
            return;
        }

        foreach ($events as $event) {
            $integrationEvent = $this->integrationEventMapper->map($event);
            if (null === $integrationEvent) {
                continue;
            }

            $this->connection()->rPush(
                self::QUEUE,
                json_encode([
                    'id' => Str::orderedUuid()->toString(),
                    'module' => $this->module->name(),
                    'status' => 0,
                    'event' => $integrationEvent::class,
                    'eventPayload' => $integrationEvent
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