<?php

namespace Module\Booking\Common\Domain\Event\Status;

use Module\Booking\Common\Domain\Entity\AbstractBooking;

class BookingCancelledFee extends AbstractStatusEvent
{
    public function __construct(
        AbstractBooking $booking,
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
