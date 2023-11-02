<?php

namespace Module\Booking\Domain\Shared\Event\Status;

use Module\Booking\Domain\Booking\Booking;

class BookingNotConfirmed extends AbstractStatusEvent
{
    public function __construct(
        Booking $booking,
        public readonly string $reason
    ) {
        parent::__construct($booking);
    }

    public function payload(): ?array
    {
        return [
            'reason' => $this->reason
        ];
    }
}
