<?php

namespace Sdk\Module\Event;

use Illuminate\Support\Facades\Queue;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventPublisherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMapperInterface;
use Sdk\Module\Contracts\ModuleInterface;

class DomainEventPublisher implements DomainEventPublisherInterface
{
    private const QUEUE = 'integration_events';

    private IntegrationEventMapperInterface $integrationEventMapper;

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

            Queue::push(new SendIntegrationEventJob($this->module->name(), $integrationEvent), '', self::QUEUE);
        }
    }
}