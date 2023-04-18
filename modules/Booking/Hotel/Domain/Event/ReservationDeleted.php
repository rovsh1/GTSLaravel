<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EditEventInterface;
use Module\Booking\Common\Domain\Event\EventInterface;
use Module\Booking\Common\Domain\ValueObject\ReservationTypeEnum;

class ReservationDeleted implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType,
        public readonly string $reservationName,
    ) {}
}
