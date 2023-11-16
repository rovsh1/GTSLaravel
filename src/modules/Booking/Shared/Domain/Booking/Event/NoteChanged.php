<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;

final class NoteChanged extends AbstractBookingEvent
{
    public function __construct(
        public readonly Booking $booking,
        public readonly ?string $noteBefore,
    ) {
        parent::__construct($this->booking);
    }
}
