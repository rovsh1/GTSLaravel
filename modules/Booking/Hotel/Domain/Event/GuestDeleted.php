<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\Common\Domain\Event\CalculationChangesEventInterface;
use Module\Booking\Common\Domain\Event\EditEventInterface;
use Module\Booking\Hotel\Domain\Entity\Booking;

class GuestDeleted extends AbstractBookingEvent implements EditEventInterface, CalculationChangesEventInterface
{
    public function __construct(
        BookingInterface|Booking $booking,
        public readonly int $roomId,
        public readonly int $guestId,
        public readonly string $guestName,
    ) {
        parent::__construct($booking);
    }
}
