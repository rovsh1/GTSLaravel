<?php

namespace Sdk\Shared\Support\IntegrationEvent;

use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventPublisherInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventMapperInterface;

class SendIntegrationEventListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly IntegrationEventPublisherInterface $integrationEventPublisher,
        private readonly array $mappers,
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        $mapper = $this->build($event);
        if (null === $mapper) {
            return;
        }

        $this->integrationEventPublisher->publish(
            $mapper->map($event)
        );
    }

    private function build(DomainEventInterface $event): ?IntegrationEventMapperInterface
    {
        foreach ($this->mappers as $domainEvent => $mapperClass) {
            if (is_a($event, $domainEvent)) {
                return $this->container->make($mapperClass);
            }
        }

        return null;
    }
}
