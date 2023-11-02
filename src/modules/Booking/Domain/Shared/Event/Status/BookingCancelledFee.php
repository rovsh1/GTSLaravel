<?php

namespace Module\Booking\Domain\Shared\Event\Status;


use Module\Booking\Domain\Booking\Booking;

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
