<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperFactory;
use Sdk\Module\Contracts\ContextInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventPublisherInterface;

class PublishIntegrationEventListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly ModuleInterface $module,
        private readonly MapperFactory $mapperFactory,
        private readonly IntegrationEventPublisherInterface $integrationEventPublisher,
        private readonly ContextInterface $context,
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        $mapper = $this->mapperFactory->build($event);
        if (!$mapper) {
            return;
        }

        $context = $this->context->toArray();
        foreach ($mapper->map($event) as $event) {
            $this->integrationEventPublisher->publish($this->module->name(), $event, $context);
        }
    }
}
