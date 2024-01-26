<?php

namespace Sdk\Booking\Event\Status;


use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;

final class BookingCancelledFee extends AbstractStatusEvent implements InvoiceBecomeDeprecatedEventInterface
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
