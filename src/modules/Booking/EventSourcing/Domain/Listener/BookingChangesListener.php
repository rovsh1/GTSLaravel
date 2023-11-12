<?php

namespace Module\Booking\EventSourcing\Domain\Listener;

use Module\Booking\EventSourcing\Domain\Repository\BookingLogRepositoryInterface;
use Module\Booking\EventSourcing\Domain\Service\BookingComparator\AttributesComparator;
use Module\Booking\EventSourcing\Domain\ValueObject\BookingEventEnum;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMessage;

class BookingChangesListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly BookingLogRepositoryInterface $changesLogRepository,
        private readonly AttributesComparator $attributesComparator,
    ) {
    }

    public function handle(IntegrationEventMessage $message): void
    {
        $modifiedAttributes = $this->attributesComparator->compare(
            $message->payload['dataBefore'],
            $message->payload['dataAfter']
        );

        if (empty($modifiedAttributes)) {
            return;
        }

        $this->changesLogRepository->register(
            new BookingId($message->payload['bookingId']),
            BookingEventEnum::ATTRIBUTE_MODIFIED,
            array_map(fn($attr) => $attr->serialize(), $modifiedAttributes),
            $message->context
        );
    }
}
