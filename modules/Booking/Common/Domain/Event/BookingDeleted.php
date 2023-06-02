<?php

namespace Module\Booking\Common\Domain\Event;

use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;

class BookingDeleted implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly BookingTypeEnum $reservationType,
        public readonly string $reservationName,
    ) {}
}
