<?php

namespace Module\Booking\Shared\Domain\Shared\Event\Status;


use Module\Booking\Shared\Domain\Booking\Booking;

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
