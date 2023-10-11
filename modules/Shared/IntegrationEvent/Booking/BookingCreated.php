<?php

namespace Module\Shared\IntegrationEvent\Booking;

use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Shared\Contracts\Event\IntegrationEventInterface;
use Module\Shared\Enum\ServiceTypeEnum;

final class BookingCreated implements IntegrationEventInterface
{
    public function __construct(
        public readonly int $bookingId,
        public readonly BookingStatusEnum $status,
        public readonly ServiceTypeEnum $serviceType,
    ) {
    }
}