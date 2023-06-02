<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Event\EventInterface;
use Module\Booking\Common\Domain\Event\StatusEventInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

class BookingStatusChanged implements EventInterface, StatusEventInterface
{
    public function __construct(
        public readonly Booking $booking,
        public readonly BookingStatusEnum $oldStatus,
    ) {}
}
