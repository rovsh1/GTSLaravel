<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EditEventInterface;
use Module\Booking\Common\Domain\Event\EventInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;

class ReservationDeleted implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly BookingTypeEnum $reservationType,
        public readonly string $reservationName,
    ) {}
}
