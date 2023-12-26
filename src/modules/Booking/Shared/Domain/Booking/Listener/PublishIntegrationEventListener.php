<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperFactory;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventPublisherInterface;

class PublishIntegrationEventListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly MapperFactory $mapperFactory,
        private readonly IntegrationEventPublisherInterface $integrationEventPublisher
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        $mapper = $this->mapperFactory->build($event);
        if (!$mapper) {
            return;
        }

        $this->integrationEventPublisher->publish(...$mapper->map($event));
    }
}
