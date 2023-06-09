<?php

namespace Module\Booking\Common\Domain\Event\Status;

use Module\Booking\Common\Domain\Entity\Booking;

class BookingCancelledFee extends AbstractStatusEvent
{
    public function __construct(
        Booking $booking,
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
