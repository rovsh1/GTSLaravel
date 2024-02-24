<?php

namespace Sdk\Booking\Event\Status;

use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\Event\BookingCancelledEventInterface;

final class BookingCancelled extends AbstractStatusEvent implements InvoiceBecomeDeprecatedEventInterface,
                                                                    BookingCancelledEventInterface
{
    public function status(): StatusEnum
    {
        return $this->booking->status()->value();
    }
}
