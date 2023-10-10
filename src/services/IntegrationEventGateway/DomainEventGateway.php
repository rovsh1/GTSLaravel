<?php

namespace Service\IntegrationEventGateway;

use Illuminate\Support\Facades\Queue;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class DomainEventGateway
{
    private const QUEUE = 'integration_events';

    public function __construct(
        private readonly DomainEventMapper $domainEventMapper
    ) {
    }

    public function register(DomainEventInterface $domainEvent): void
    {
        $integrationEvent = $this->domainEventMapper->map($domainEvent);

        Queue::push(new SendIntegrationEventJob($integrationEvent), '', self::QUEUE);
    }
}