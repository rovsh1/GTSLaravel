<?php

namespace Module\Booking\Domain\Shared\Event\Status;

use Module\Booking\Domain\Shared\Entity\BookingInterface;

class BookingNotConfirmed extends AbstractStatusEvent
{
    public function __construct(
        BookingInterface $booking,
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
