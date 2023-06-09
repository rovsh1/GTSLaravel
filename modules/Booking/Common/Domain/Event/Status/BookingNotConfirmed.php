<?php

namespace Module\Booking\Common\Domain\Event\Status;

use Module\Booking\Common\Domain\Entity\Booking;

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
