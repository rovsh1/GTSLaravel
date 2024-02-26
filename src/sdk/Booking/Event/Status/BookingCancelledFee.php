<?php

namespace Sdk\Booking\Event\Status;


use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\Event\BookingCancelledEventInterface;

final class BookingCancelledFee extends AbstractStatusEvent implements InvoiceBecomeDeprecatedEventInterface,
                                                                       BookingCancelledEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly float $cancelFeeAmount
    ) {
        parent::__construct($booking);
    }

    public function status(): StatusEnum
    {
        return $this->booking->status()->value();
    }

    public function payload(): ?array
    {
        return [
            'cancelFeeAmount' => $this->cancelFeeAmount
        ];
    }
}
