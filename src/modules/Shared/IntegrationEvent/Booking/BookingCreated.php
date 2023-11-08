<?php

namespace Module\Shared\IntegrationEvent\Booking;

use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

final class BookingCreated implements IntegrationEventInterface
{
    public function __construct(
        public readonly int $bookingId,
        public readonly BookingStatusEnum $status,
        public readonly ServiceTypeEnum $serviceType,
    ) {
    }
}
