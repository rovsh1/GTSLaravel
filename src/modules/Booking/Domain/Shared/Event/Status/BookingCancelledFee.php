<?php

namespace Module\Booking\Domain\Shared\Event\Status;

use Module\Booking\Domain\Shared\Entity\BookingInterface;

class BookingCancelledFee extends AbstractStatusEvent
{
    public function __construct(
        BookingInterface $booking,
        public readonly float $cancelFeeAmount
    ) {
        parent::__construct($booking);
    }

    public function payload(): ?array
    {
        return [
            'cancelFeeAmount' => $this->cancelFeeAmount
        ];
    }
}
