<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Support\Event\AbstractBookingEvent;

final class NoteChanged extends AbstractBookingEvent
{
    public function __construct(
        Booking $booking,
        public readonly ?string $noteBefore,
    ) {
        parent::__construct($booking);
    }
}
