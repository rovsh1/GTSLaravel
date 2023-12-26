<?php

namespace Module\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Sdk\Booking\IntegrationEvent\StatusUpdated;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class StatusDescriptor extends AbstractDescriptor implements DescriptorInterface
{
    public function __construct(
        private readonly BookingStatusStorageInterface $bookingStatusStorage,
    ) {}

    public function build(IntegrationEventInterface $event): DescriptionDto
    {
        assert($event instanceof StatusUpdated);

        return new DescriptionDto(
            group: EventGroupEnum::STATUS_UPDATED,
            field: 'status',
            description: $this->bookingStatusStorage->getName($event->status),
            before: null,
            after: $event->status->value
//            $this->bookingStatusStorage->getColor($event->status),
        );
    }
}
