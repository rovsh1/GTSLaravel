<?php

namespace Module\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Sdk\Booking\IntegrationEvent\ClientPriceChanged;
use Sdk\Booking\IntegrationEvent\RequestSent;
use Sdk\Booking\IntegrationEvent\StatusUpdated;
use Sdk\Booking\IntegrationEvent\SupplierPriceChanged;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;

class DescriptorFactory
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    public function build(IntegrationEventInterface $event): DescriptorInterface
    {
        return $this->container->make($this->getClass($event));
    }

    private function getClass(IntegrationEventInterface $event): string
    {
        return match ($event::class) {
            StatusUpdated::class => StatusDescriptor::class,
            RequestSent::class => RequestDescriptor::class,
            ClientPriceChanged::class, SupplierPriceChanged::class => PriceDescriptor::class,
            default => DefaultDescriptor::class
//    EventGroupEnum::STATUS_UPDATED => $this->statusDescriptor,
////            EventGroupEnum::PRICE_CHANGED => $this->statusDtoFactory,
//    EventGroupEnum::REQUEST_SENT => $this->requestDescriptor,
//    default => $this->defaultDescriptor
        };
    }

}